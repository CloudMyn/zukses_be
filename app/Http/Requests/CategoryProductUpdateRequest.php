<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CategoryProduct;
use Illuminate\Validation\Rule;

class CategoryProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $category = $this->route('category');

        // ADMIN can update any category
        if ($user->tipe_user === 'ADMIN') {
            return true;
        }

        // PEDAGANG cannot update system categories (level 0)
        if ($user->tipe_user === 'PEDAGANG' && $category->level_kategori === 0) {
            return false;
        }

        return in_array($user->tipe_user, ['ADMIN', 'PEDAGANG']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'nama_kategori' => 'required|string|max:255',
            'slug_kategori' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('tb_kategori_produk', 'slug_kategori')->ignore($category->id),
            ],
            'deskripsi_kategori' => 'nullable|string|max:1000',
            'gambar_kategori' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'icon_kategori' => 'nullable|image|mimes:png,svg,ico|max:1024',
            'id_kategori_induk' => [
                'nullable',
                'integer',
                Rule::exists('tb_kategori_produk', 'id'),
                function ($attribute, $value, $fail) use ($category) {
                    // Prevent self-reference
                    if ($value == $category->id) {
                        $fail('Kategori tidak bisa menjadi induk dari dirinya sendiri.');
                        return;
                    }

                    if ($value && $this->user()->tipe_user !== 'ADMIN') {
                        // Non-admin users cannot move to/from system categories
                        $newParent = CategoryProduct::find($value);
                        if ($newParent && $newParent->level_kategori === 0) {
                            $fail('Hanya admin yang bisa memindahkan kategori ke kategori utama.');
                        }
                    }

                    // Prevent circular reference
                    if ($value) {
                        $newParent = CategoryProduct::find($value);
                        if ($newParent && $this->wouldCreateCircularReference($newParent, $category)) {
                            $fail('Tidak dapat memindahkan kategori yang akan membuat referensi melingkar.');
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
            $category = $this->route('category');
            $slug = str_slug($this->nama_kategori);

            // Ensure slug is unique
            $originalSlug = $slug;
            $counter = 1;
            while (CategoryProduct::where('slug_kategori', $slug)
                ->where('id', '!=', $category->id)
                ->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $this->merge(['slug_kategori' => $slug]);
        }

        // Set boolean defaults
        if ($this->has('is_kategori_aktif')) {
            $this->merge(['is_kategori_aktif' => $this->boolean('is_kategori_aktif')]);
        }
        if ($this->has('is_kategori_featured')) {
            $this->merge(['is_kategori_featured' => $this->boolean('is_kategori_featured')]);
        }
    }

    /**
     * Check if moving category would create circular reference
     */
    private function wouldCreateCircularReference(CategoryProduct $newParent, CategoryProduct $category, int $depth = 0): bool
    {
        if ($depth > 10) { // Prevent infinite loop
            return true;
        }

        // Check if new parent is a descendant of current category
        if ($newParent->id_kategori_induk) {
            $parent = CategoryProduct::find($newParent->id_kategori_induk);
            if ($parent) {
                if ($parent->id === $category->id) {
                    return true;
                }
                return $this->wouldCreateCircularReference($parent, $category, $depth + 1);
            }
        }

        return false;
    }
}