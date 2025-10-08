<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSessionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $session = $this->route('session');
        return $this->user()->tipe_user === 'ADMIN' || $this->user()->id === $session->id_user;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'aktivitas_terakhir' => 'integer',
        ];
    }
}