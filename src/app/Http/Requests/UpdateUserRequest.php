<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email'            => [
                'email',
                Rule::unique('users')->ignore(auth()->id())
            ],
            'name'             => 'nullable|min:2',
            'current_password' => 'required|current_password',
            'password'         => 'required|min:6|confirmed',
        ];

        if ($this->request->has('without_password')) {
            unset($rules['current_password'], $rules['password']);
        }

        return $rules;
    }
}
