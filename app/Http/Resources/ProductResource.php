<?php

namespace App\Http\Resources;

use App\Repositories\EclubApiRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        if (!$this['image']) {
            $image = (new EclubApiRepository())->getProductImagesBySku([$this['sku']]);
            if (isset($image['data'][$this['sku']])) {
                $this['image'] = $image['data'][$this['sku']];
            }
        }

        return [
            'sku'          => $this['sku'],
            'name'         => $this['name'],
            'price'        => $this['price'],
            'quantity'     => $this['quantity'],
            'barcode'      => $this['barcode'],
            'image'        => $this['image'] ?? null,
            'unit'         => $this['unit'] ?? null,
            'min_quantity' => isset($this['min_quantity']) ? $this['min_quantity'] : null,
            'is_weighable' => !empty($this['unit']) && !($this['unit'] === 'шт' || is_null($this['unit'])),
//            'is_weighable' => is_null($this['unit']),
        ];
    }
}
