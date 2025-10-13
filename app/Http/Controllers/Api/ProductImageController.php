<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use App\Http\Resources\ProductImageResource;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $productImages = ProductImage::paginate(15);
            return ProductImageResource::collection($productImages);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data gambar produk: ' . $e->getMessage()
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
                'id_produk' => 'required|exists:tb_produk,id',
                'id_harga_varian' => 'nullable|exists:harga_varian_produk,id',
                'url_gambar' => 'required|string|max:255',
                'alt_text' => 'nullable|string|max:255',
                'urutan_gambar' => 'required|integer|min:0',
                'is_gambar_utama' => 'required|boolean',
                'tipe_gambar' => 'required|in:GALERI,DESKRIPSI,VARIAN',
            ]);

            $productImage = ProductImage::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Gambar produk berhasil dibuat',
                'data' => new ProductImageResource($productImage)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat gambar produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductImage $productImage)
    {
        try {
            return new ProductImageResource($productImage);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data gambar produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductImage $productImage)
    {
        try {
            $validatedData = $request->validate([
                'id_produk' => 'sometimes|required|exists:tb_produk,id',
                'id_harga_varian' => 'nullable|exists:harga_varian_produk,id',
                'url_gambar' => 'sometimes|required|string|max:255',
                'alt_text' => 'nullable|string|max:255',
                'urutan_gambar' => 'sometimes|required|integer|min:0',
                'is_gambar_utama' => 'sometimes|required|boolean',
                'tipe_gambar' => 'sometimes|required|in:GALERI,DESKRIPSI,VARIAN',
            ]);

            $productImage->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Gambar produk berhasil diperbarui',
                'data' => new ProductImageResource($productImage)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui gambar produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductImage $productImage)
    {
        try {
            $productImage->delete();

            return response()->json([
                'success' => true,
                'message' => 'Gambar produk berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus gambar produk: ' . $e->getMessage()
            ], 500);
        }
    }
}