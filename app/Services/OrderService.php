<?php

namespace App\Services;

use App\Exceptions\AssemblerException;
use App\Exceptions\AssemblerExceptionInterface;
use App\Helpers\Status;
use App\Repositories\CrmApiRepository;
use App\Repositories\EclubApiRepository;
use App\Repositories\StockApiRepository;
use App\Repositories\StoreRepository;
use App\Repositories\UserOrderRepository;
use App\Repositories\UserRepository;

class OrderService
{
    public function __construct(
        private StockApiRepository  $stockApiRepository,
        private CrmApiRepository    $crmApiRepository,
        private EclubApiRepository  $eclubApiRepository,
        private UserOrderRepository $userOrderRepository,
        private UserRepository      $userRepository,
        private StoreRepository     $storeRepository,
    ) {}

    public function getByStore($store, $filters = [])
    {
        $orders     = collect($this->crmApiRepository->getOrdersByStoreNumber($store->number));
        $reserves   = collect($this->stockApiRepository->getReservesByStoreNumber($store->number));

        $ids        = $orders->pluck('number');
        $userOrders = $this->userOrderRepository->getByOrderIds($ids);
        $finished   = $userOrders->where('status', Status::FINISHED)->pluck('order_id')->toArray();

        $orders     = $orders->whereNotIn('number', $finished);
        $orders     = $this->reformat($orders, [
            'reserves'      => $reserves->keyBy('owner_id'),
            'user_orders'   => $userOrders->keyBy('order_id'),
        ]);
        $filters['status'] = Status::NEW;
        $orders     = $this->applyFilters($orders, $filters);
        $orders     = $orders->sortBy('priority', descending: true);
        return $orders;
    }

    public function getByStoreAndId($store, $orderId)
    {
        $order = $this->crmApiRepository->getOrderByStoreNumberAndId($store->number, $orderId);

        if (! $order) {
            abort(404, "Order with $orderId not found");
        }

        $imageAndMinQuantity = $this->eclubApiRepository->getImageAndMinQuantityProducts(array_column($order['positions'], 'sku'))['data'];

        $reserve = $this->stockApiRepository->getReserveByStoreAndOrderNumbers($store->number, $order['number']);
        $order['reserve_id'] = $reserve['id'] ?? null;

        $order = $this->userOrderRepository->withPositionColumnBySku($order, $imageAndMinQuantity,'image');
        return $this->userOrderRepository->withPositionColumnBySku($order, $imageAndMinQuantity,'min_quantity');
    }

    public function reformat($orders, $data) {
        $orders = $orders->map(function ($order) use ($data) {
            $order['reserve_id'] = $data['reserves'][$order['number']]['id'] ?? null;
            $order['status'] = $data['user_orders'][$order['number']]['status'] ?? 'new';
            $order['priority'] = $data['user_orders'][$order['number']]['priority'] ?? Status::getPriority($order['status']);
            return $order;
        });

        return $orders;
    }

    public function allocate($storeNumber, $orderId)
    {
        $onlineUsers    = $this->userRepository->getOnlineByStore($storeNumber);

        if (empty($onlineUsers)) {
            throw new AssemblerException(AssemblerExceptionInterface::NO_ONLINE_USERS);
        }

        $store  = $this->storeRepository->getByNumber($storeNumber);
        $order  = $this->getByStoreAndId($store, $orderId);
        $data   = [
            'user_id'       => $onlineUsers->first()->id,
            'order_id'      => $orderId,
            'reserve_id'    => $order['reserve_id'],
            'status'        => Status::PROCESSING,
            'priority'      => Status::getPriority(Status::PROCESSING),
            'order_data'    => $order,
        ];
        $this->userOrderRepository->assemble($data);

        return $onlineUsers->first();
    }

    private function applyFilters($orders, array $filters)
    {
        if (! empty($filters['status'])) {
            $orders = $orders->whereIn('status', $filters['status']);
        }
       $orders->whereNotNull('reserve_id');

        return $orders;
    }
}
