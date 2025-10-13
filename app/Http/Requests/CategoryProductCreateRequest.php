<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CategoryProduct;
use Illuminate\Validation\Rule;

class CategoryProductCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return in_array($user->tipe_user, ['ADMIN', 'PEDAGANG']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_kategori' => 'required|string|max:255',
            'slug_kategori' => 'nullable|string|max:255|unique:tb_kategori_produk,slug_kategori',
            'deskripsi_kategori' => 'nullable|string|max:1000',
            'gambar_kategori' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'icon_kategori' => 'nullable|image|mimes:png,svg,ico|max:1024',
            'id_kategori_induk' => [
                'nullable',
                'integer',
                Rule::exists('tb_kategori_produk', 'id'),
                function ($attribute, $value, $fail) {
                    if ($value && $this->user()->tipe_user !== 'ADMIN') {
                        // Non-admin users cannot create top-level categories
                        if (CategoryProduct::find($value)?->level_kategori === 0) {
                            $fail('Hanya admin yang bisa membuat sub-kategori dari kategori utama.');
                        }
                    }
                },
                function ($attribute, $value, $fail) {
                    if ($value) {
                        // Prevent circular reference
                        $parent = CategoryProduct::find($value);
                        if ($parent && $this->preventCircularReference($parent)) {
                            $fail('Tidak dapat membuat kategori dengan referensi melingkar.');
                        }
                    }
                },
            ],
            'urutan_tampilan' => 'nullable|integer|min:0|max:9999',
            'is_kategori_aktif' => 'nullable|boolean',
            'is_kategori_featured' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.max' => 'Nama kategori maksimal 255 karakter.',
            'slug_kategori.unique' => 'Slug kategori sudah digunakan.',
            'deskripsi_kategori.max' => 'Deskripsi kategori maksimal 1000 karakter.',
            'gambar_kategori.image' => 'File harus berupa gambar.',
            'gambar_kategori.mimes' => 'Format gambar yang diizinkan: jpeg, png, jpg, gif, webp.',
            'gambar_kategori.max' => 'Ukuran gambar maksimal 2MB.',
            'icon_kategori.image' => 'File harus berupa gambar.',
            'icon_kategori.mimes' => 'Format ikon yang diizinkan: png, svg, ico.',
            'icon_kategori.max' => 'Ukuran ikon maksimal 1MB.',
            'id_kategori_induk.exists' => 'Kategori induk tidak ditemukan.',
            'urutan_tampilan.integer' => 'Urutan tampilan harus berupa angka.',
            'urutan_tampilan.min' => 'Urutan tampilan minimal 0.',
            'urutan_tampilan.max' => 'Urutan tampilan maksimal 9999.',
            'meta_title.max' => 'Meta title maksimal 255 karakter.',
            'meta_description.max' => 'Meta description maksimal 500 karakter.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nama_kategori' => 'nama kategori',
            'slug_kategori' => 'slug kategori',
            'deskripsi_kategori' => 'deskripsi kategori',
            'gambar_kategori' => 'gambar kategori',
            'icon_kategori' => 'ikon kategori',
            'id_kategori_induk' => 'kategori induk',
            'urutan_tampilan' => 'urutan tampilan',
            'is_kategori_aktif' => 'status aktif',
            'is_kategori_featured' => 'status unggulan',
            'meta_title' => 'meta title',
            'meta_description' => 'meta description',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Generate slug from name if not provided
        if (!$this->has('slug_kategori') && $this->has('nama_kategori')) {
            $this->merge([
                'slug_kategori' => str_slug($this->nama_kategori)
            ]);
        }

        // Set default values
        $this->merge([
            'is_kategori_aktif' => $this->boolean('is_kategori_aktif', true),
            'is_kategori_featured' => $this->boolean('is_kategori_featured', false),
        ]);

        // Set level based on parent category
        if ($this->has('id_kategori_induk') && $this->id_kategori_induk) {
            $parent = CategoryProduct::find($this->id_kategori_induk);
            if ($parent) {
                $this->merge([
                    'level_kategori' => $parent->level_kategori + 1,
                ]);
            }
        } else {
            $this->merge([
                'level_kategori' => 0,
            ]);
        }
    }

    /**
     * Prevent circular reference in category hierarchy
     */
    private function preventCircularReference(CategoryProduct $parent, int $depth = 0): bool
    {
        if ($depth > 10) { // Prevent infinite loop
            return true;
        }

        if ($parent->id_kategori_induk) {
            $grandParent = CategoryProduct::find($parent->id_kategori_induk);
            if ($grandParent) {
                return $this->preventCircularReference($grandParent, $depth + 1);
            }
        }

        return false;
    }
}