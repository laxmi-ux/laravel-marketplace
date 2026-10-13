<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:15',
            ],

            'seller_type' => [
                'nullable',
                'in:individual,company',
            ],

            'business_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'business_category' => [
                'nullable',
                'string',
                'max:255',
            ],

            'gst_number' => [
                'nullable',
                'string',
                'max:20',
            ],

            'business_address' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'city' => [
                'nullable',
                'string',
                'max:255',
            ],

            'state' => [
                'nullable',
                'string',
                'max:255',
            ],

            'pincode' => [
                'nullable',
                'string',
                'max:10',
            ],
        ];
    }
}