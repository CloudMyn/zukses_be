<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductUpdateRequest extends FormRequest
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
            'id_seller' => 'sometimes|required|exists:penjual,id',
            'id_admin' => 'nullable|exists:users,id',
            'sku' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('tb_produk', 'sku')->ignore($this->product->id ?? $this->route('product'))
            ],
            'nama_produk' => 'sometimes|required|string|max:500',
            'slug_produk' => [
                'sometimes',
                'required',
                'string',
                'max:500',
                Rule::unique('tb_produk', 'slug_produk')->ignore($this->product->id ?? $this->route('product'))
            ],
            'deskripsi_lengkap' => 'nullable|string',
            'kondisi_produk' => 'sometimes|required|in:BARU,BEKAS',
            'status_produk' => 'sometimes|required|in:DRAFT,AKTIF,TIDAK_AKTIF,DITOLAK,HAPUS',
            'berat_paket' => 'sometimes|required|numeric|min:0',
            'panjang_paket' => 'sometimes|required|numeric|min:0',
            'lebar_paket' => 'sometimes|required|numeric|min:0',
            'tinggi_paket' => 'sometimes|required|numeric|min:0',
            'harga_minimum' => 'sometimes|required|numeric|min:0',
            'harga_maximum' => 'sometimes|required|numeric|min:0|gte:harga_minimum',
            'jumlah_stok' => 'sometimes|required|integer|min:0',
            'stok_minimum' => 'sometimes|required|integer|min:0',
            'jumlah_terjual' => 'sometimes|required|integer|min:0',
            'jumlah_dilihat' => 'sometimes|required|integer|min:0',
            'jumlah_difavoritkan' => 'sometimes|required|integer|min:0',
            'rating_produk' => 'sometimes|required|numeric|min:0|max:5',
            'jumlah_ulasan' => 'sometimes|required|integer|min:0',
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
            'status_produk.required' => 'Status produk wajib diisi.',
            'berat_paket.required' => 'Berat paket wajib diisi.',
            'berat_paket.numeric' => 'Berat paket harus berupa angka.',
            'berat_paket.min' => 'Berat paket tidak boleh negatif.',
            'panjang_paket.required' => 'Panjang paket wajib diisi.',
            'panjang_paket.numeric' => 'Panjang paket harus berupa angka.',
            'panjang_paket.min' => 'Panjang paket tidak boleh negatif.',
            'lebar_paket.required' => 'Lebar paket wajib diisi.',
            'lebar_paket.numeric' => 'Lebar paket harus berupa angka.',
            'lebar_paket.min' => 'Lebar paket tidak boleh negatif.',
            'tinggi_paket.required' => 'Tinggi paket wajib diisi.',
            'tinggi_paket.numeric' => 'Tinggi paket harus berupa angka.',
            'tinggi_paket.min' => 'Tinggi paket tidak boleh negatif.',
            'harga_minimum.required' => 'Harga minimum wajib diisi.',
            'harga_minimum.numeric' => 'Harga minimum harus berupa angka.',
            'harga_minimum.min' => 'Harga minimum tidak boleh negatif.',
            'harga_maximum.required' => 'Harga maksimum wajib diisi.',
            'harga_maximum.numeric' => 'Harga maksimum harus berupa angka.',
            'harga_maximum.min' => 'Harga maksimum tidak boleh negatif.',
            'harga_maximum.gte' => 'Harga maksimum harus lebih besar atau sama dengan harga minimum.',
            'jumlah_stok.required' => 'Jumlah stok wajib diisi.',
            'jumlah_stok.integer' => 'Jumlah stok harus berupa angka bulat.',
            'jumlah_stok.min' => 'Jumlah stok tidak boleh negatif.',
            'stok_minimum.required' => 'Stok minimum wajib diisi.',
            'stok_minimum.integer' => 'Stok minimum harus berupa angka bulat.',
            'stok_minimum.min' => 'Stok minimum tidak boleh negatif.',
            'jumlah_terjual.required' => 'Jumlah terjual wajib diisi.',
            'jumlah_terjual.integer' => 'Jumlah terjual harus berupa angka bulat.',
            'jumlah_terjual.min' => 'Jumlah terjual tidak boleh negatif.',
            'jumlah_dilihat.required' => 'Jumlah dilihat wajib diisi.',
            'jumlah_dilihat.integer' => 'Jumlah dilihat harus berupa angka bulat.',
            'jumlah_dilihat.min' => 'Jumlah dilihat tidak boleh negatif.',
            'jumlah_difavoritkan.required' => 'Jumlah difavoritkan wajib diisi.',
            'jumlah_difavoritkan.integer' => 'Jumlah difavoritkan harus berupa angka bulat.',
            'jumlah_difavoritkan.min' => 'Jumlah difavoritkan tidak boleh negatif.',
            'rating_produk.required' => 'Rating produk wajib diisi.',
            'rating_produk.numeric' => 'Rating produk harus berupa angka.',
            'rating_produk.min' => 'Rating produk minimal 0.',
            'rating_produk.max' => 'Rating produk maksimal 5.',
            'jumlah_ulasan.required' => 'Jumlah ulasan wajib diisi.',
            'jumlah_ulasan.integer' => 'Jumlah ulasan harus berupa angka bulat.',
            'jumlah_ulasan.min' => 'Jumlah ulasan tidak boleh negatif.',
            'is_produk_unggulan.boolean' => 'Produk unggulan harus bernilai true atau false.',
            'is_produk_preorder.boolean' => 'Produk preorder harus bernilai true atau false.',
            'is_cod.boolean' => 'COD harus bernilai true atau false.',
            'is_approved.boolean' => 'Approved harus bernilai true atau false.',
            'is_product_varian.boolean' => 'Varian produk harus bernilai true atau false.',
            'waktu_preorder.integer' => 'Waktu preorder harus berupa angka bulat.',
            'waktu_preorder.min' => 'Waktu preorder minimal 1 hari.',
            'waktu_preorder.max' => 'Waktu preorder maksimal 365 hari.',
            'garansi_produk.max' => 'Garansi produk maksimal 100 karakter.',
            'etalase_kategori.json' => 'Etalase kategori harus dalam format JSON.',
            'tag_produk.json' => 'Tag produk harus dalam format JSON.',
            'meta_title.max' => 'Meta title maksimal 255 karakter.',
            'meta_description.max' => 'Meta description maksimal 500 karakter.',
        ];
    }
}