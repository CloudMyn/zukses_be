<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatConversationCreateRequest extends FormRequest
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
            'nama_obrolan' => 'required|string|max:255',
            'deskripsi_obrolan' => 'nullable|string',
            'jenis_obrolan' => 'required|in:PRIVAT,GROUP,ORDER,SUPPORT',
            'is_group' => 'required|boolean',
            'is_active' => 'required|boolean',
            'id_pembuat' => 'required|exists:users,id',
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
            'nama_obrolan.required' => 'Nama obrolan wajib diisi',
            'nama_obrolan.string' => 'Nama obrolan harus berupa teks',
            'nama_obrolan.max' => 'Nama obrolan maksimal 255 karakter',
            'jenis_obrolan.required' => 'Jenis obrolan wajib diisi',
            'jenis_obrolan.in' => 'Jenis obrolan tidak valid',
            'is_group.required' => 'Status grup wajib diisi',
            'is_group.boolean' => 'Status grup harus berupa nilai boolean',
            'is_active.required' => 'Status aktif wajib diisi',
            'is_active.boolean' => 'Status aktif harus berupa nilai boolean',
            'id_pembuat.required' => 'ID pembuat wajib diisi',
            'id_pembuat.exists' => 'Pembuat obrolan yang dipilih tidak valid',
        ];
    }
}