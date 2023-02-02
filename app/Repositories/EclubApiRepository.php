<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class EclubApiRepository
{
    private $client;

    public function __construct()
    {
        $this->client = Http::baseUrl(config('api.eclub.host').'/api/assembler');
        $this->client->withHeaders(['token' => '3H0JiVIrhYfZAZ2y3518']);
    }

    public function compselections($store)
    {
        $this->client->withHeaders(['Accept' => 'application/json', 'content-type' => 'application/json']);
        return $this->client->get('compselections', [
            'shop_number' => $store
        ])->json();
    }

    public function getCompilationById($id, $store)
    {
        return $this->client->get("compilations/$id", [
            'shop_number' => $store
        ])->json();
    }
    public function isProductsThisMarket($marketNumber, $sku = [])
    {
        return $this->client->get("markets/$marketNumber/products-exists", [
            'sku' => $sku
        ])->json()['data'];
    }

    public function getProductById($id) {
        return $this->client->get("products/$id")->json();
    }

    public function disableCancelOrder($number) {
        return $this->client->post("orders/$number/disable-cancel")->json();
    }

    public function getProductImagesBySku($sku = []) {
        return $this->client->get("products/images", ['sku' => $sku])->json();
    }
    public function getImageAndMinQuantityProducts($sku = []) {
        return $this->client->get("products/image-and-min-qty", ['sku' => $sku])->json();
    }
}
