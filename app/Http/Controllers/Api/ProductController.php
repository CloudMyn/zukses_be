<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::paginate(15);
            return ProductResource::collection($products);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data produk: ' . $e->getMessage()
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
                'id_seller' => 'required|exists:penjual,id',
                'id_admin' => 'nullable|exists:users,id',
                'sku' => 'required|string|unique:tb_produk,sku',
                'nama_produk' => 'required|string',
                'slug_produk' => 'required|string|unique:tb_produk,slug_produk',
                'deskripsi_lengkap' => 'nullable|string',
                'kondisi_produk' => 'required|in:BARU,BEKAS,REFURBISHED',
                'status_produk' => 'required|in:DRAFT,AKTIF,TIDAK_AKTIF,HAPUS,DITOLAK',
                'berat_paket' => 'nullable|numeric|min:0',
                'panjang_paket' => 'nullable|integer|min:0',
                'lebar_paket' => 'nullable|integer|min:0',
                'tinggi_paket' => 'nullable|integer|min:0',
                'harga_minimum' => 'required|numeric|min:0',
                'harga_maximum' => 'required|numeric|min:0',
                'jumlah_stok' => 'required|integer|min:0',
                'stok_minimum' => 'required|integer|min:0',
                'jumlah_terjual' => 'required|integer|min:0',
                'jumlah_dilihat' => 'required|integer|min:0',
                'jumlah_difavoritkan' => 'required|integer|min:0',
                'rating_produk' => 'required|numeric|min:0|max:5',
                'jumlah_ulasan' => 'required|integer|min:0',
                'is_produk_unggulan' => 'required|boolean',
                'is_produk_preorder' => 'required|boolean',
                'is_cod' => 'required|boolean',
                'is_approved' => 'required|boolean',
            ]);

            $product = Product::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dibuat',
                'data' => new ProductResource($product)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        try {
            return new ProductResource($product);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            $validatedData = $request->validate([
                'id_seller' => 'sometimes|required|exists:penjual,id',
                'id_admin' => 'nullable|exists:users,id',
                'sku' => 'sometimes|required|string|unique:tb_produk,sku,' . $product->id,
                'nama_produk' => 'sometimes|required|string',
                'slug_produk' => 'sometimes|required|string|unique:tb_produk,slug_produk,' . $product->id,
                'deskripsi_lengkap' => 'nullable|string',
                'kondisi_produk' => 'sometimes|required|in:BARU,BEKAS,REFURBISHED',
                'status_produk' => 'sometimes|required|in:DRAFT,AKTIF,TIDAK_AKTIF,HAPUS,DITOLAK',
                'berat_paket' => 'nullable|numeric|min:0',
                'panjang_paket' => 'nullable|integer|min:0',
                'lebar_paket' => 'nullable|integer|min:0',
                'tinggi_paket' => 'nullable|integer|min:0',
                'harga_minimum' => 'sometimes|required|numeric|min:0',
                'harga_maximum' => 'sometimes|required|numeric|min:0',
                'jumlah_stok' => 'sometimes|required|integer|min:0',
                'stok_minimum' => 'sometimes|required|integer|min:0',
                'jumlah_terjual' => 'sometimes|required|integer|min:0',
                'jumlah_dilihat' => 'sometimes|required|integer|min:0',
                'jumlah_difavoritkan' => 'sometimes|required|integer|min:0',
                'rating_produk' => 'sometimes|required|numeric|min:0|max:5',
                'jumlah_ulasan' => 'sometimes|required|integer|min:0',
                'is_produk_unggulan' => 'sometimes|required|boolean',
                'is_produk_preorder' => 'sometimes|required|boolean',
                'is_cod' => 'sometimes|required|boolean',
                'is_approved' => 'sometimes|required|boolean',
            ]);

            $product->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui',
                'data' => new ProductResource($product)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus produk: ' . $e->getMessage()
            ], 500);
        }
    }
}