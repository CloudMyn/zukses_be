<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatMessageCreateRequest extends FormRequest
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
            'id_obrolan' => 'required|exists:obrolan,id',
            'id_pengirim' => 'required|exists:users,id',
            'isi_pesan' => 'required_if:jenis_pesan,TEKS|string|max:1000',
            'jenis_pesan' => 'required|in:TEKS,GAMBAR,VIDEO,DOKUMEN,LOKASI',
            'id_pesan_induk' => 'nullable|exists:pesan_obrolan,id',
            'metadata' => 'nullable|json',
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
            'id_pengirim.required' => 'ID pengirim wajib diisi',
            'id_pengirim.exists' => 'Pengirim yang dipilih tidak valid',
            'isi_pesan.required_if' => 'Isi pesan wajib diisi untuk pesan teks',
            'isi_pesan.string' => 'Isi pesan harus berupa teks',
            'isi_pesan.max' => 'Isi pesan maksimal 1000 karakter',
            'jenis_pesan.required' => 'Jenis pesan wajib diisi',
            'jenis_pesan.in' => 'Jenis pesan tidak valid',
            'id_pesan_induk.exists' => 'Pesan induk yang dipilih tidak valid',
            'metadata.json' => 'Metadata harus berupa format JSON yang valid',
        ];
    }
}