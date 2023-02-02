<?php

namespace App\Services;

use App\Exceptions\AssemblerException;
use App\Exceptions\AssemblerExceptionInterface;
use App\Repositories\UserRepository;
use App\Repositories\WorkEntryRepository;
use Carbon\Carbon;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private WorkEntryRepository $workEntryRepository
    ) {}

    public function startWork($user) {
        if (! $this->userRepository->isOnline($user)) {
            $this->userRepository->startWork($user);
            $this->workEntryRepository->enter([
                'entered_at' => Carbon::now(),
                'user_id'    => $user->id
            ]);
        }
    }

    /**
     * @throws AssemblerException
     */
    public function stopWork($user) {
        if ($this->userRepository->hasActiveOrders($user)) {
            throw new AssemblerException(AssemblerExceptionInterface::HAS_ACTIVE_ORDER);
        }

        if ($this->userRepository->isOnline($user)) {
            $this->userRepository->stopWork($user);
            $this->workEntryRepository->exit([
                'user_id' => $user->id
            ]);
        }
    }

    public function getWeekStats(mixed $user, mixed $week)
    {
        if ($week == 'current') {
            $startOfWeek = Carbon::now()->startOf('week');
            $endOfWeek   = Carbon::now()->endOfWeek();
        } else if ($week == 'prev') {
            $startOfWeek = Carbon::now()->previous('week');
            $endOfWeek   = Carbon::now()->previous('week')->endOfWeek();
        } else {
            $startOfWeek = Carbon::now()->addWeek()->startOf('week');
            $endOfWeek   = Carbon::now()->addWeek()->endOfWeek();
        }

        return $this->userRepository->getWeekStats($user, $startOfWeek, $endOfWeek);
    }
}
