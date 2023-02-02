<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class StockApiRepository
{
    protected $client;

    public function __construct() {
        $this->client = Http::baseUrl(config('api.stock.host'))
            ->withHeaders(['Authorization' => 'Bearer '.config('api.stock.apiKey')]);
    }

    public function getReservesByStoreNumber($number) {
        return $this->client->get("v1/reserve/$number")->json();
    }

    public function getReserveByStoreAndOrderNumbers($number, $orderNumber) {
        $reserves = collect($this->getReservesByStoreNumber($number));

        return $reserves->where('owner_id', $orderNumber)->first();
    }

    public function cancelReserve($reserveId, array $data)
    {
        return $this->client->post("v1/reserve/$reserveId/cancel", $data)->json();
    }

    public function confirmReserve($reserveId, array $data)
    {
        return $this->client->post("v1/reserve/$reserveId/confirm", $data)->json();
    }
}
