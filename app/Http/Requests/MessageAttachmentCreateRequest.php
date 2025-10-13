<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageAttachmentCreateRequest extends FormRequest
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
            'nama_file' => 'required|string|max:255',
            'path_file' => 'required|string|max:500',
            'url_file' => 'required|url',
            'jenis_file' => 'required|in:GAMBAR,VIDEO,AUDIO,DOKUMEN,LAINNYA',
            'ukuran_file' => 'required|integer|min:0',
            'mime_type' => 'required|string|max:100',
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
            'nama_file.required' => 'Nama file wajib diisi',
            'nama_file.string' => 'Nama file harus berupa teks',
            'nama_file.max' => 'Nama file maksimal 255 karakter',
            'path_file.required' => 'Path file wajib diisi',
            'path_file.string' => 'Path file harus berupa teks',
            'path_file.max' => 'Path file maksimal 500 karakter',
            'url_file.required' => 'URL file wajib diisi',
            'url_file.url' => 'URL file harus berupa format URL yang valid',
            'jenis_file.required' => 'Jenis file wajib diisi',
            'jenis_file.in' => 'Jenis file tidak valid',
            'ukuran_file.required' => 'Ukuran file wajib diisi',
            'ukuran_file.integer' => 'Ukuran file harus berupa angka',
            'ukuran_file.min' => 'Ukuran file minimal 0',
            'mime_type.required' => 'Mime type wajib diisi',
            'mime_type.string' => 'Mime type harus berupa teks',
            'mime_type.max' => 'Mime type maksimal 100 karakter',
        ];
    }
}