<?php

namespace App\Services;

use App\Exceptions\AssemblerException;
use App\Exceptions\AssemblerExceptionInterface;
use App\Helpers\Status;
use App\Repositories\CrmApiRepository;
use App\Repositories\DeviceTokenRepository;
use App\Repositories\EclubApiRepository;
use App\Repositories\StockApiRepository;
use App\Repositories\StoreRepository;
use App\Repositories\UserOrderRepository;
use App\Repositories\UserRepository;

class DeviceTokenService
{
    public function __construct(private DeviceTokenRepository $deviceTokenRepository)
    {
    }
    public function getByUser($user)
    {
        return  $this->deviceTokenRepository->getByUser($user);
    }
    public function updateOrCreate($data, $user) {
        $this->deviceTokenRepository->deleteUserTokens($user);
//          'installation_id' => 'nullable',
//            'device_token' => 'required'
        return $this->deviceTokenRepository->updateOrCreate([
            'value'           => $data['device_token'] ?? null,
            'installation_id' => $data['installation_id'] ?? null,
            'user_id'         => $user?->id
        ]);
    }

    public function deleteAll($user)
    {
        $this->deviceTokenRepository->deleteUserTokens($user);
    }

    public function delete(mixed $token)
    {
        $this->deviceTokenRepository->delete($token);
    }
}
