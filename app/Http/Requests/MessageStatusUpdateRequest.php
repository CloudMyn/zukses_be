<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageStatusUpdateRequest extends FormRequest
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
        $id = $this->route('messageStatus'); // Get the messageStatus ID from the route

        return [
            'id_pesan' => 'sometimes|required|exists:pesan_obrolan,id',
            'id_user' => 'sometimes|required|exists:users,id',
            'status_pesan' => 'sometimes|required|in:TERKIRIM,DITERIMA,DIBACA,DITARIK',
            'tanggal_status' => 'sometimes|required|date',
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
            'status_pesan.required' => 'Status pesan wajib diisi',
            'status_pesan.in' => 'Status pesan tidak valid',
            'tanggal_status.required' => 'Tanggal status wajib diisi',
            'tanggal_status.date' => 'Tanggal status harus berupa format tanggal yang valid',
        ];
    }
}