<?php

namespace App\Http\Requests\Api\Auth;

use App\Models\User;
use App\Rules\UserNameRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

class UserRegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', new UserNameRule],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone_number' => ['required', 'phone:RW', 'phone:RW,NATIONAL', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'village_id' => ['required', 'string', 'ulid'],
        ];
    }

    public function messages(): array
    {
        return [
            'village_id' => ['required' => 'Please select your Village'],
            'phone_number' => ['phone' => 'Please use valid phone number.'],
        ];
    }
}
