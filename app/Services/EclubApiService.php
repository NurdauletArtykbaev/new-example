<?php

namespace App\Services;

use App\Repositories\EclubApiRepository;
use Illuminate\Http\Request;

class EclubApiService
{
    public function __construct(private EclubApiRepository $eclubApiRepository) {}

    public function compselections($store)
    {
        return $this->eclubApiRepository->compselections($store);
    }

    public function getCompilationById($id, $store) {
        $compliations =  $this->eclubApiRepository->getCompilationById($id, $store);
//        dd($compliations);
        foreach ($compliations['data'] as &$compliation) {
            $compliation['custom_name'] = $compliation['name'];
        }
        return  $compliations;
//        return $this->eclubApiRepository->getCompilationById($id, $store);
    }
    public function isProductsThisMarket($marketNumber, $sku = []) {
        $isProductsThisMarket =  $this->eclubApiRepository->isProductsThisMarket($marketNumber, $sku);
        return  $isProductsThisMarket['data'];
    }

    public function getProductById($id) {
        return $this->eclubApiRepository->getProductById($id);
    }
}
