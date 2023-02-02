<?php

use App\Helpers\DeviceTokenHelper;
use App\Helpers\NotificationHelper;
use App\Jobs\Notification\NotifyNewOrder;
use App\Models\NewOrderNumberForNotification;
use App\Models\User;
use App\Models\UserOrder;
use App\Repositories\CrmApiRepository;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
//
//    $tokens = User::first()
//        ->deviceTokens()
//        ->where('driver', DeviceTokenHelper::DRIVER_FIREBASE)
//        ->get();
//    $tokens = array_merge($tokens->pluck('value')->whereNotNull()->toArray(), $tokens->pluck('installation_id')->whereNotNull()->toArray());
//
////    $storeNumber = 2010;
////    $orders = collect((new CrmApiRepository())->getOrdersByStoreNumber($storeNumber));
//
//    $users = User::where('id', 1)->get();
//
//    if ($users->isEmpty()) {
//        return false;
//    }
//
//    $newOrderNumbersForNotification = NewOrderNumberForNotification::whereIn('order_number', $orders->pluck('number')->toArray())
//        ->select('order_number')
//        ->get()
//        ->pluck('order_number')
//        ->toArray();
//    $newOrders = array_diff($orders->pluck('number')->toArray(), $newOrderNumbersForNotification);
//    $orders = $orders->whereIn('number', $newOrders);
//    if ($orders->isEmpty()) {
//        return false;
//    }
//
//    $notification = \App\Models\Notification::where('key',NotificationHelper::KEY_NEW_ORDER)->first();
//     NotifyNewOrder::dispatch($users,[], $notification, [], [] )->onQueue('notification');
//     NotifyNewOrder::dispatch($users,$orders->pluck('number')->toArray(), $notification, [], [] )->onQueue('notification');

      return view('welcome');
});
