<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatParticipantUpdateRequest extends FormRequest
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
        $id = $this->route('chatParticipant'); // Get the chatParticipant ID from the route

        return [
            'id_obrolan' => 'sometimes|required|exists:obrolan,id',
            'id_user' => 'sometimes|required|exists:users,id|unique:partisipan_obrolan,id_user,' . $id . ',id,id_obrolan,' . $this->input('id_obrolan', optional($this->route('chatParticipant'))->id_obrolan ?? 'NULL'),
            'status_partisipan' => 'sometimes|required|in:ACTIVE,LEFT,BANNED,INVITED',
            'is_admin' => 'sometimes|required|boolean',
            'is_muted' => 'sometimes|required|boolean',
            'tanggal_join' => 'sometimes|required|date',
            'tanggal_keluar' => 'nullable|date',
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
            'id_obrolan.required' => 'ID obrolan wajib diisi',
            'id_obrolan.exists' => 'Obrolan yang dipilih tidak valid',
            'id_user.required' => 'ID user wajib diisi',
            'id_user.exists' => 'User yang dipilih tidak valid',
            'id_user.unique' => 'User sudah menjadi partisipan dalam obrolan ini',
            'status_partisipan.required' => 'Status partisipan wajib diisi',
            'status_partisipan.in' => 'Status partisipan tidak valid',
            'is_admin.required' => 'Status admin wajib diisi',
            'is_admin.boolean' => 'Status admin harus berupa nilai boolean',
            'is_muted.required' => 'Status mute wajib diisi',
            'is_muted.boolean' => 'Status mute harus berupa nilai boolean',
            'tanggal_join.required' => 'Tanggal join wajib diisi',
            'tanggal_join.date' => 'Tanggal join harus berupa format tanggal yang valid',
            'tanggal_keluar.date' => 'Tanggal keluar harus berupa format tanggal yang valid',
        ];
    }
}