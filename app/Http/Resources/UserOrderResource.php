<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserOrderResource extends JsonResource
{
    protected bool $isDetail = false;

    public function toArray($request)
    {
        $data = (new OrderResource($this['order_data']))->details($this->isDetail)->toArray($request);
        $data['id']         = $this['id'];
        $data['status']     = trans('status.'.$this['status']);
        $data['status_raw'] = $this['status'];
        $data['reserve_id'] = $this['reserve_id'] ?? $data['reserve_id'];

        if ($this->isDetail) {
            $data['products'] = ($data['products'])->toArray($request);

            foreach ($data['products'] as &$product) {
                $item = array_filter($this['items'] ?? [], fn($item) => isset($item['sku']) && $item['sku'] == $product['sku']);
                $item = array_shift($item);

                $product['is_processed'] = !!$item && isset($item['quantity']) && $item['quantity'] > 0 &&   (!isset($item['is_out_stock']) || !$item['is_out_stock']);
                $product['is_out_stock'] = $item['is_out_stock'] ?? false;
            }

            $data['items']       = $this['items'] ?? [];
            $data['created_at']  = $this['created_at']->format('H:m:s');
            $data['finished_at'] = $this['finished_at']?->format('H:m:s');
            $data['time_spent']  = $this['time_spent'];
        }

        return $data;
    }

    public function details(bool $isDetail) {
        $this->isDetail = $isDetail;

        return $this;
    }
}
