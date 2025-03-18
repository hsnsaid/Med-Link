<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
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
            'name'=>['required','string'],
            'gender'=>['string'],
            'email'=>['required','email'],
            'password'=>['required','string','confirmed'],
            'phone_number'=>['string'],
            'speciality'=>['string'],
            'formations'=>['string'],
            'type_consultation'=>['string'],
            'city'=>['string'],
            'street'=>['string'],
            'localisation'=>['string'],
            'picture'=>['image']        
        ];
    }
}
