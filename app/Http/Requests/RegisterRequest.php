<?php

namespace App\Http\Requests;

use App\Helpers\StringFormatterHelper;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules() {

//        $this->request->set('phone',  (new StringFormatterHelper())->onlyDigits($this->phone));
        $this->merge(['phone' => (new StringFormatterHelper())->onlyDigits($this->input('phone'))]);
//        $this->request->add(['phone' => (new StringFormatterHelper())->onlyDigits($this->input('phone'))] );
//        dd($this->phone)
//        dd($this->phone);
        return [
            'phone'    => 'required|unique:users,personal_number',
            'password' => 'required'
        ];
    }
}
