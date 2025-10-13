<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageReactionUpdateRequest extends FormRequest
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
        $id = $this->route('messageReaction'); // Get the messageReaction ID from the route

        return [
            'id_pesan' => 'sometimes|required|exists:pesan_obrolan,id',
            'id_user' => 'sometimes|required|exists:users,id',
            'jenis_reaksi' => 'sometimes|required|in:LIKE,LOVE,HAHA,WOW,SAD,ANGRY,THUMBS_UP,THUMBS_DOWN',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'id_pesan.required' => 'ID pesan wajib diisi',
            'id_pesan.exists' => 'Pesan yang dipilih tidak valid',
            'id_user.required' => 'ID user wajib diisi',
            'id_user.exists' => 'User yang dipilih tidak valid',
            'jenis_reaksi.required' => 'Jenis reaksi wajib diisi',
            'jenis_reaksi.in' => 'Jenis reaksi tidak valid',
        ];
    }
}