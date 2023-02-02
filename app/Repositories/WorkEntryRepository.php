<?php

namespace App\Repositories;

use App\Models\WorkEntry;
use Carbon\Carbon;

class WorkEntryRepository
{
    public function enter($data) {
        return WorkEntry::query()->create($data);
    }

    public function exit($data) {
        $lastEntry = WorkEntry::query()
            ->where('user_id', $data['user_id'])
            ->whereNull('exited_at')
            ->orderBy('id', 'desc')
            ->first();
        $lastEntry->exited_at       = Carbon::now();
        $lastEntry->seconds_worked  = Carbon::parse($lastEntry->exited_at)->diffInSeconds($lastEntry->entered_at);
        $lastEntry->saveOrFail();
    }
}
