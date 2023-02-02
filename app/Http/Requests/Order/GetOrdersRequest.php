<?php

namespace App\Http\Requests\Order;

use App\Helpers\Status;
use Illuminate\Foundation\Http\FormRequest;

class GetOrdersRequest extends FormRequest
{
    public function rules() {
        return [
            'status' => 'nullable|array',
            'status.*' => 'required|in:'.implode(',', array_keys(Status::PRIORITIES)),
        ];
    }
}
