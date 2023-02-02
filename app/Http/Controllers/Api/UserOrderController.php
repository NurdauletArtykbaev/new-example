<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Status;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserOrderResource;
use App\Models\Market;
use App\Models\UserOrder;
use App\Repositories\CrmApiRepository;
use App\Repositories\EclubApiRepository;
use App\Services\UserOrderService;
use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    public function __construct(private UserOrderService $userOrderService, private EclubApiRepository $eclubApiRepository, private CrmApiRepository $crmApiRepository)
    {
        $this->middleware('isOnline')->except('index', 'show', 'changeStatusCallback');
    }

    public function index(Request $request)
    {
        $data = [
            'user_id' => $request->user()->id,
        ];
        $userOrders = $this->userOrderService->list($data);

        return UserOrderResource::collection($userOrders);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'order_id' => 'required|unique:user_orders,order_id',
            'reserve_id' => 'required|int',
        ]);
        $userOrder = $this->userOrderService->assemble($request->all(), $request->user());

        $this->handleDisableCancelOrder($userOrder->order_data['number'], $userOrder->order_data['positions']);

        $resource = new UserOrderResource($userOrder);
        $resource->details(true);

        return $resource;
    }

    public function show(UserOrder $user_order)
    {
        $userOrder = new UserOrderResource($user_order);
        $userOrder->details(true);

        return $userOrder;
    }

    public function update(Request $request, UserOrder $user_order)
    {
        $validated = $this->validate($request, [
            'items' => 'nullable|array',
            'items.*' => 'required|array',
            'items.*.sku' => 'required|int',
            'items.*.quantity' => 'nullable',
        ]);

        $userOrder = $this->userOrderService->edit($user_order, $validated);
        $userOrder = new UserOrderResource($userOrder);
        $userOrder->details(true);

        return $userOrder;
    }

    public function finish(UserOrder $userOrder)
    {
        $this->userOrderService->finish($userOrder);

        return response()->noContent();
    }

    public function cancel(Request $request, UserOrder $userOrder)
    {
        $validated = $this->validate($request, [
            'items' => 'required|array',
            'items.*' => 'required|int'
        ]);
        $this->userOrderService->cancel($userOrder, $validated);

        return response()->noContent();
    }

    public function changeStatusCallback(Request $request)
    {
        $this->validate($request, [
            'order_id' => 'required|exists:user_orders,order_id',
            'status' => 'required|in:' . Status::DELIVERED . ',' . Status::ON_DELIVERY
        ]);
        $userOrder = $this->userOrderService->changeStatus($request->order_id, $request->status);

        return response()->json(['success' => true, 'user_order' => new UserOrderResource($userOrder)]);
    }

    private function handleDisableCancelOrder($orderNumber, $products)
    {
        if (count($products)) {
            $sku = array_column($products, 'sku');
            if ($this->eclubApiRepository->isProductsThisMarket(8, $sku)) {
                $this->eclubApiRepository->disableCancelOrder($orderNumber);
                $this->crmApiRepository->disableCancelOrder($orderNumber);
            }
        }
    }

}
