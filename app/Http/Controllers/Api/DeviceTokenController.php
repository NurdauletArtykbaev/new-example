<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeviceToken\StoreDeviceTokenRequest;
use App\Services\DeviceTokenService;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    public function __construct(private DeviceTokenService $deviceTokenService)
    {
    }

    public function store(StoreDeviceTokenRequest $request)
    {
        return response()->json(['data' => $this->deviceTokenService->updateOrCreate($request->validated(), $request->user())]);
    }

}
