<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatReportUpdateRequest extends FormRequest
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
        $id = $this->route('chatReport'); // Get the chatReport ID from the route

        return [
            'id_obrolan' => 'sometimes|required|exists:obrolan,id',
            'id_pelapor' => 'sometimes|required|exists:users,id',
            'id_pelanggar' => 'sometimes|required|exists:users,id',
            'jenis_pelanggaran' => 'sometimes|required|in:SPAM,KONTEN_TIDAK_SESUAI,PESAN_MENJIJIKKAN,PELECEHAN,LAINNYA',
            'deskripsi_pelanggaran' => 'sometimes|required|string',
            'bukti_pelanggaran' => 'nullable|url',
            'status_laporan' => 'sometimes|required|in:DRAFT,DITERIMA,DITINJAU,DITUTUP,DIBERKASAKAN',
            'id_admin_reviewer' => 'nullable|exists:users,id',
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
            'id_pelapor.required' => 'ID pelapor wajib diisi',
            'id_pelapor.exists' => 'Pelapor yang dipilih tidak valid',
            'id_pelanggar.required' => 'ID pelanggar wajib diisi',
            'id_pelanggar.exists' => 'Pelanggar yang dipilih tidak valid',
            'jenis_pelanggaran.required' => 'Jenis pelanggaran wajib diisi',
            'jenis_pelanggaran.in' => 'Jenis pelanggaran tidak valid',
            'deskripsi_pelanggaran.required' => 'Deskripsi pelanggaran wajib diisi',
            'deskripsi_pelanggaran.string' => 'Deskripsi pelanggaran harus berupa teks',
            'bukti_pelanggaran.url' => 'Bukti pelanggaran harus berupa URL yang valid',
            'status_laporan.in' => 'Status laporan tidak valid',
            'id_admin_reviewer.exists' => 'Admin reviewer yang dipilih tidak valid',
        ];
    }
}