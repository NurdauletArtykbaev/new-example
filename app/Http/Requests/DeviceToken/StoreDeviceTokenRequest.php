<?php

namespace App\Http\Requests\DeviceToken;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeviceTokenRequest extends FormRequest
{
    public function rules()
    {
        return [
            'installation_id' => 'nullable',
            'device_token' => 'required'
        ];
    }
}
