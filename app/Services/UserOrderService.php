<?php

namespace App\Services;

use App\Exceptions\AssemblerException;
use App\Exceptions\AssemblerExceptionInterface;
use App\Helpers\Status;
use App\Models\UserOrder;
use App\Repositories\StockApiRepository;
use App\Repositories\UserOrderRepository;
use App\Repositories\UserRepository;

class UserOrderService
{
    public function __construct(
        private UserOrderRepository $userOrderRepository,
        private UserRepository      $userRepository,
        private StockApiRepository  $stockApiRepository,
        private OrderService        $orderService
    ) {}

    public function assemble($data, mixed $user)
    {
        $store = $this->userRepository->getStore($user);
        $order = $this->orderService->getByStoreAndId($store, $data['order_id']);

        $data = [
            'user_id'       => $user->id,
            'order_id'      => $data['order_id'],
            'reserve_id'    => $order['reserve_id'] ?? $data['reserve_id'],
            'status'        => Status::PROCESSING,
            'priority'      => Status::getPriority(Status::PROCESSING),
            'order_data'    => $order,
        ];

        return $this->userOrderRepository->assemble($data);
    }

    public function list(array $data)
    {
        return $this->userOrderRepository->list($data);
    }

    public function edit(UserOrder $userOrder, array $validated)
    {
        return $this->userOrderRepository->edit($userOrder, $validated);
    }

    public function cancel(UserOrder $userOrder, $data)
    {
        $userOrder = $userOrder->load('user');
        $data = implode(',', $data['items']);
        $this->userOrderRepository->edit($userOrder, [
            'status'        => Status::CANCELED,
            'finished_at'   => now()->toDateTimeString(),
        ]);

        if (app()->environment() == 'production') {
            $this->stockApiRepository->cancelReserve($userOrder->reserve_id, [
                'reason'            => $data,
                'personal_number'   => $userOrder->user->personal_number,
                'full_name'         => $userOrder->user->full_name
            ]);
        }
    }

    public function finish(UserOrder $userOrder)
    {
        if (! $this->userOrderRepository->isCompleted($userOrder)) {
            throw new AssemblerException(AssemblerExceptionInterface::ORDER_NOT_COMPLETED);
        }

        $this->userOrderRepository->edit($userOrder, [
            'status'        => Status::FINISHED,
            'finished_at'   => now()->toDateTimeString(),
            'priority'      => Status::getPriority(Status::FINISHED)
        ]);
        $params = [
            'personal_number'   => $userOrder->user->personal_number,
            'full_name'         => $userOrder->user->full_name
        ];
        $changedQuantities = collect($userOrder->items)->whereNotNull('quantity')->where('is_out_stock', false);
//        if ($changedQuantities->isNotEmpty()) {
        $params['products'] = $changedQuantities->toArray();
//        }

        if (app()->environment() == 'production') {
            $this->stockApiRepository->confirmReserve($userOrder->reserve_id, $params);
        }
    }

    public function changeStatus($orderId, $status)
    {
        $userOrder = $this->userOrderRepository->getByOrderId($orderId);
        $userOrder = $this->userOrderRepository->edit($userOrder, ['status' => $status]);

        return $userOrder;
    }
}
