<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'full_name'         => $this->full_name,
            'personal_number'   => $this->personal_number,
            'is_online'         => $this->is_online,
            'store'             => $this->store,
            'market_name'       => $this->store->first()?->market?->name ?? null,
            'address'           => $this->store->first()?->address,
            'schedule'          => new ShiftResource($this->shift)
        ];
    }
}
