<?php

namespace App\Http\Resources;

use App\Helpers\Status;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    private $isDetail = false;

    public function toArray($request)
    {
        $status     = $this['status'] ?? 'new';

        return [
            'id'                => $this['id'],
            'number'            => $this['number'],
            'status'            => trans("status.$status"),
            'delivery_code'     => $this['delivery_code'] ?? null,
            'status_raw'        => $status,
            'priority'          => $this['priority'] ?? 0,
            'customer_name'     => $this['full_name'],
            'customer_phone'    => $this['phone'],
            'delivery_name'     => $this['delivery_name'],
            'payment_name'      => $this['payment_name'],
            'courier_phone'     => $this['courier']['phone'],
            'reserve_id'        => $this['reserve_id'] ?? null,
            'products_count'    => count($this['positions']),
            'cost'              => $this['cost'] ?? null,
            'products'          => $this->when($this->isDetail, ProductResource::collection($this['positions']))
        ];
    }

    public function details(bool $value) {
        $this->isDetail = $value;

        return $this;
    }
}
