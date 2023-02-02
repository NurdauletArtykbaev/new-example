<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class CrmApiRepository
{
    protected $client;

    public function __construct()
    {
        $this->client = Http::baseUrl(config('api.crm.host'))
            ->withHeaders(['Authorization' => 'Bearer '.config('api.crm.apiKey')]);
    }

    public function getOrdersByStoreNumber($number) {
        if (app()->environment() == 'local') {
            return json_decode(file_get_contents(__DIR__.'/orders.json'), true);
        }

        return $this->client->get("emart/orders/{$number}")->json();
    }


    public function disableCancelOrder($number) {
        if (app()->environment() == 'local') {
            return null;
        }
        return $this->client->post("emart/orders/{$number}/disable-cancel")->json();
    }

    public function getOrderByStoreNumberAndId($number, $orderId) {
        $orders = collect($this->getOrdersByStoreNumber($number));

        return $orders->where('number', $orderId)->first();
    }
}
