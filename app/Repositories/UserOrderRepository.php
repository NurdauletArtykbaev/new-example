<?php

namespace App\Repositories;

use App\Helpers\Status;
use App\Models\UserOrder;
use Carbon\Carbon;

class UserOrderRepository
{

    public function assemble($data)
    {
        return UserOrder::query()->updateOrCreate(['order_id' => $data['order_id']], $data);
    }

    public function getAssemblersByOrderIds($assemblers)
    {
        return UserOrder::query()->with('user')->whereIn('order_id', $assemblers)->get();
    }

    public function finish(UserOrder $userOrder)
    {
        $params = [
            'finished_at'   => Carbon::now()
        ];

        $userOrder->update($params);
    }

    public function getByOrderId($orderId) {
        return UserOrder::query()->where('order_id', $orderId)->first();
    }

    public function list(array $data)
    {
        $query = UserOrder::with('user');

        if (! empty($data['user_id'])) {
            $query->where('user_id', $data['user_id']);
        }

        $query
            ->orderBy('priority', 'desc')
            ->orderBy('id', 'desc');

        return $query->get();
    }

    public function edit(UserOrder $userOrder, array $validated)
    {
        $userOrder->fill($validated);
        $userOrder->saveOrFail();

        return $userOrder;
    }

    public function isCompleted(UserOrder $userOrder) {

        $orderItems = $userOrder->order_data['positions'];
        $orderItems = array_column($orderItems, 'sku');
        return empty(array_diff($orderItems, array_column($userOrder->items ?? [], 'sku') ?? []));
    }

    public function getByOrderIds($ids) {
        return UserOrder::query()->orderIdsIn($ids)->get();
    }

    public function getFinishedByOrderIds($ids) {
        return UserOrder::query()
            ->orderIdsIn($ids)
            ->finished()
            ->get();
    }

    public function filterActive($orders)
    {
        $orders     = collect($orders);
        $orderIds   = $orders->pluck('number');
        $userOrders = UserOrder::query()
            ->select('order_id')
            ->whereIn('order_id', $orderIds)
            ->pluck('order_id')
            ->toArray();

        return $orders->whereNotIn('number', $userOrders)->toArray();
    }

    public function withImages($order, $images) {
        foreach ($order['positions'] as &$position) {
            $position['image'] = $images[$position['sku']] ?? null;
        }

        return $order;
    }


    public function withPositionColumnBySku($order, $images, $column) {
        foreach ($order['positions'] as &$position) {
            $position[$column] = $images[$position['sku']][$column] ?? null;
        }

        return $order;
    }
}
