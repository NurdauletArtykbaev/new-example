<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EclubApiService;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;

class EclubApiController extends Controller
{
    public function __construct(private EclubApiService $eclubApiService) {}

    public function compselections(Request $request) {
        $store = $request->user()->store->first()?->number;
        if (! $store) {
            return response()->json(['message' => 'Пользователь не привязан к магазину.'], 400);
        }
        return response()->json($this->eclubApiService->compselections($store));
    }


    public function getCompilationById(Request $request, $id) {
        $store = $request->user()->store->first()?->number;

        if (! $store) {
            return response()->json(['message' => 'Пользователь не привязан к магазину.'], 400);
        }

        return response()->json($this->eclubApiService->getCompilationById($id, $store));
    }

    public function getProductById($id) {
        return response()->json($this->eclubApiService->getProductById($id));
    }
}
