<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>['string'],
            'email'=>['email','unique:users,email'],
            'password'=>['required','string',Password::min(6)->numbers()],
            'phone_number'=>['string'],
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge(['phone_number'=>$this->phoneNumber]);
    }
}
