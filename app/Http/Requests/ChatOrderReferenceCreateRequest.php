<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatOrderReferenceCreateRequest extends FormRequest
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
            'id_order' => 'required|exists:tb_order,id',
            'id_pesan' => 'required|exists:pesan_obrolan,id',
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
            'id_order.required' => 'ID order wajib diisi',
            'id_order.exists' => 'Order yang dipilih tidak valid',
            'id_pesan.required' => 'ID pesan wajib diisi',
            'id_pesan.exists' => 'Pesan yang dipilih tidak valid',
        ];
    }
}