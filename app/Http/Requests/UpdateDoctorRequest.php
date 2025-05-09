<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorRequest extends FormRequest
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
            'email'=>['email','unique:doctors,email'],
            'password'=>['string'],
            'gender'=>['string'],
            'phone_number'=>['string'],
            'speciality'=>['string'],
            'type_consultation'=>['string'],
            'city'=>['string'],
            'street'=>['string'],
            'picture'=>['image'],
            'status'=>['string']
        ];
    }
}
