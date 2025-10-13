<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageEditCreateRequest extends FormRequest
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
            'id_pesan' => 'required|exists:pesan_obrolan,id',
            'id_user' => 'required|exists:users,id',
            'isi_pesan_lama' => 'required|string',
            'isi_pesan_baru' => 'required|string',
            'tanggal_edit' => 'required|date',
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
            'isi_pesan_lama.required' => 'Isi pesan lama wajib diisi',
            'isi_pesan_lama.string' => 'Isi pesan lama harus berupa teks',
            'isi_pesan_baru.required' => 'Isi pesan baru wajib diisi',
            'isi_pesan_baru.string' => 'Isi pesan baru harus berupa teks',
            'tanggal_edit.required' => 'Tanggal edit wajib diisi',
            'tanggal_edit.date' => 'Tanggal edit harus berupa format tanggal yang valid',
        ];
    }
}