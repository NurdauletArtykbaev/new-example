<?php

namespace App\Repositories;

use App\Models\DeviceToken;

class DeviceTokenRepository
{
    public function getByUser($user)
    {
        return DeviceToken::whereUserId($user->id)->get();
    }

    public function updateOrCreate($data)
    {
        return DeviceToken::updateOrCreate($data, $data);
    }

    public function exists($token, $user)
    {
        return DeviceToken::whereValue($token)->whereUserId($user->id)->exists();
    }

    public function delete(mixed $token)
    {
        return DeviceToken::whereValue($token)->delete();
    }

    public function deleteUserTokens($user, $driver = null)
    {
        $deviceTokens = $user->deviceTokens()->when($driver, fn($query) => $query->where('driver', $driver))->get();
        foreach ($deviceTokens as $deviceToken) {
            $this->delete($deviceToken->value);
        }
    }
}
