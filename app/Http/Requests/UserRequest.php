<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'store'             => 'max:1',
            'first_name'        => 'required',
            'email'             => 'required|unique:users,email,' . request()->route('id'),
            'personal_number'   => 'nullable|required_without:email|unique:users,personal_number,' . request()->route('id'),
            'shifts'            => 'max:1'
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'store.max' => 'Работник может принадлежать только одному магизину',
            'shifts.max' => 'Работник может иметь только одну смену',
        ];
    }
}
