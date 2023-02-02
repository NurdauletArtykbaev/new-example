<?php

namespace App\Repositories;

use App\Facades\DateFormatter;
use App\Helpers\Status;
use App\Models\User;
use Carbon\Carbon;

class UserRepository
{
    public function getStore(User $user) {
        return $user->store()->first();
    }

    public function stopWork(User $user) {
        $user->is_online = false;
        $user->saveOrFail();
    }

    public function startWork(User $user) {
        $user->is_online = true;
        $user->saveOrFail();
    }

    public function hasActiveOrders(User $user) {
        return $user->orders()->where('status', Status::PROCESSING)->exists();
    }

    public function isOnline(User $user) {
        return $user->isOnline();
    }

    public function getWeekStats(mixed $user, $startOfWeek, $endOfWeek)
    {
        $ordersCount = $user->orders()
            ->whereIn('status', [Status::FINISHED, Status::CANCELED])
            ->whereBetween('finished_at', [$startOfWeek, $endOfWeek])
            ->get();

        $timeWorked = $user->workEntries()->whereBetween('entered_at', [$startOfWeek, $endOfWeek])->get();
        $details    = [];

        for ($i = 0; $i < 7; $i++) {
            $day = (clone $startOfWeek)->addDays($i);
            $details[] = [
                'day'           => mb_ucfirst($day->getTranslatedMinDayName()),
                'orders_count'  => $ordersCount
                    ->filter(function ($item) use ($day) {
                        return $item->finished_at->toDateString() == $day->toDateString();
                    })
                    ->count(),
                'hours_count'   => DateFormatter::secondsToHumanReadable(
                    $timeWorked
                        ->filter(function ($item) use ($day) {
                            return $item->entered_at->toDateString() == $day->toDateString();
                        })
                        ->sum('seconds_worked')
                ),
            ];
        }

        return [
            'date'          => "с $startOfWeek->day $startOfWeek->monthName - $endOfWeek->day $endOfWeek->monthName",
            'total_orders_count'  => $ordersCount->count(),
            'total_hours_worked'  => DateFormatter::secondsToHumanReadable($timeWorked->sum('seconds_worked')),
            'details'       => $details
        ];
    }

    public function getOnlineByStore($storeNumber) {
        return User::query()
            ->where('is_online', true)
            ->whereHas('store', fn($query) => $query->where('number', $storeNumber))
            ->withCount(['orders' => fn($query) => $query->whereDate('created_at', Carbon::today())])
            ->orderBy('orders_count')
            ->get();
    }
}
