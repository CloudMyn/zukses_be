<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'id_seller' => 'required|exists:tb_penjual,id',
            'id_admin' => 'nullable|exists:users,id',
            'sku' => 'required|string|unique:tb_produk,sku|max:255',
            'nama_produk' => 'required|string|max:500',
            'slug_produk' => 'required|string|unique:tb_produk,slug_produk|max:500',
            'deskripsi_lengkap' => 'nullable|string',
            'kondisi_produk' => 'required|in:BARU,BEKAS',
            'status_produk' => 'required|in:DRAFT,AKTIF,TIDAK_AKTIF,DITOLAK,HAPUS',
            'berat_paket' => 'required|numeric|min:0',
            'panjang_paket' => 'required|numeric|min:0',
            'lebar_paket' => 'required|numeric|min:0',
            'tinggi_paket' => 'required|numeric|min:0',
            'harga_minimum' => 'required|numeric|min:0',
            'harga_maximum' => 'required|numeric|min:0|gte:harga_minimum',
            'jumlah_stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'jumlah_terjual' => 'required|integer|min:0',
            'jumlah_dilihat' => 'required|integer|min:0',
            'jumlah_difavoritkan' => 'required|integer|min:0',
            'rating_produk' => 'required|numeric|min:0|max:5',
            'jumlah_ulasan' => 'required|integer|min:0',
            'is_produk_unggulan' => 'boolean',
            'is_produk_preorder' => 'boolean',
            'is_cod' => 'boolean',
            'is_approved' => 'boolean',
            'is_product_varian' => 'boolean',
            'waktu_preorder' => 'nullable|integer|min:1|max:365',
            'garansi_produk' => 'nullable|string|max:100',
            'etalase_kategori' => 'nullable|json',
            'tag_produk' => 'nullable|json',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'id_seller.required' => 'ID penjual wajib diisi.',
            'id_seller.exists' => 'ID penjual tidak valid.',
            'sku.required' => 'SKU produk wajib diisi.',
            'sku.unique' => 'SKU produk sudah digunakan.',
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'slug_produk.required' => 'Slug produk wajib diisi.',
            'slug_produk.unique' => 'Slug produk sudah digunakan.',
            'kondisi_produk.required' => 'Kondisi produk wajib diisi.',
            'harga_maximum.gte' => 'Harga maksimum harus lebih besar atau sama dengan harga minimum.',
        ];
    }
}