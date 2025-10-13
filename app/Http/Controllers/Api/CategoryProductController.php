<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryProduct;
use App\Http\Resources\CategoryProductResource;
use Illuminate\Http\Request;

class CategoryProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = CategoryProduct::paginate(15);
            return CategoryProductResource::collection($categories);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data kategori produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama_kategori' => 'required|string|unique:tb_kategori_produk,nama_kategori',
                'slug_kategori' => 'required|string|unique:tb_kategori_produk,slug_kategori',
                'deskripsi_kategori' => 'nullable|string',
                'gambar_kategori' => 'nullable|string',
                'icon_kategori' => 'nullable|string',
                'id_kategori_induk' => 'nullable|exists:tb_kategori_produk,id',
                'level_kategori' => 'required|integer|min:0',
                'urutan_tampilan' => 'required|integer|min:0',
                'is_kategori_aktif' => 'required|boolean',
                'is_kategori_featured' => 'required|boolean',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string',
            ]);

            $category = CategoryProduct::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Kategori produk berhasil dibuat',
                'data' => new CategoryProductResource($category)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat kategori produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoryProduct $categoryProduct)
    {
        try {
            return new CategoryProductResource($categoryProduct);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data kategori produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoryProduct $categoryProduct)
    {
        try {
            $validatedData = $request->validate([
                'nama_kategori' => 'sometimes|required|string|unique:tb_kategori_produk,nama_kategori,' . $categoryProduct->id,
                'slug_kategori' => 'sometimes|required|string|unique:tb_kategori_produk,slug_kategori,' . $categoryProduct->id,
                'deskripsi_kategori' => 'nullable|string',
                'gambar_kategori' => 'nullable|string',
                'icon_kategori' => 'nullable|string',
                'id_kategori_induk' => 'nullable|exists:tb_kategori_produk,id',
                'level_kategori' => 'sometimes|required|integer|min:0',
                'urutan_tampilan' => 'sometimes|required|integer|min:0',
                'is_kategori_aktif' => 'sometimes|required|boolean',
                'is_kategori_featured' => 'sometimes|required|boolean',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string',
            ]);

            $categoryProduct->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Kategori produk berhasil diperbarui',
                'data' => new CategoryProductResource($categoryProduct)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui kategori produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryProduct $categoryProduct)
    {
        try {
            $categoryProduct->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kategori produk berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus kategori produk: ' . $e->getMessage()
            ], 500);
        }
    }
}