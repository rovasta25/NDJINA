<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PushSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'endpoint' => ['required', 'url', 'max:500'],
            'keys.p256dh' => ['required', 'string', 'max:255'],
            'keys.auth' => ['required', 'string', 'max:255'],
            'contentEncoding' => ['nullable', 'string', 'max:20'],
        ];
    }
}
