<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\GetOrdersRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function index(GetOrdersRequest $request) {
        $filters = $request->validated();
//        $store = $request->user()->store->first();
//        $orders = $this->orderService->getByStore($store, $filters);

        $orders = collect();
        return OrderResource::collection($orders);
    }

    public function show(Request $request, $order) {
//        $store  = $request->user()->store->first();
//        $order  = $this->orderService->getByStoreAndId($store, $order);

//        if (! $order) {
//            abort(404);
//        }

        $order = collect();
        $resource = new OrderResource($order);
        $resource->details(true);

        return $resource;
    }

    public function allocate(Request $request) {
        $this->validate($request, [
            'order_id'      => 'required|int|unique:user_orders,order_id',
            'store_number'  => 'required|exists:stores,number',
        ]);
        $assembler = $this->orderService->allocate($request->store_number, $request->order_id);

        return response()->json(['success' => true, 'assembler' => new UserResource($assembler)]);
    }
}
