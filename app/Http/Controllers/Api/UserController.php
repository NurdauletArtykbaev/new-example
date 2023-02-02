<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function me(Request $request) {
        $user = $request->user();
        $user->load('store.market');
        return new UserResource($request->user());
    }

    public function weekStats(Request $request) {
        $this->validate($request, ['week' => 'nullable|in:current,prev,next']);

        return response()->json([
            'data' => $this->userService->getWeekStats($request->user(), $request->week ?? 'current')
        ]);
    }

    public function startWork(Request $request) {
        $this->userService->startWork($request->user());

        return response()->noContent();
    }

    public function stopWork(Request $request) {
        $this->userService->stopWork($request->user());

        return response()->noContent();
    }
}
