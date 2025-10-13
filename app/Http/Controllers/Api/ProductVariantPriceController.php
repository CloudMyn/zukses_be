<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductVariantPrice;
use App\Http\Resources\ProductVariantPriceResource;
use Illuminate\Http\Request;

class ProductVariantPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $productVariantPrices = ProductVariantPrice::paginate(15);
            return ProductVariantPriceResource::collection($productVariantPrices);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data harga varian produk: ' . $e->getMessage()
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
                'produk_id' => 'required|exists:tb_produk,id',
                'gambar' => 'nullable|string|max:255',
                'harga' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:0',
                'kode_varian' => 'nullable|string|max:255',
            ]);

            $productVariantPrice = ProductVariantPrice::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Harga varian produk berhasil dibuat',
                'data' => new ProductVariantPriceResource($productVariantPrice)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat harga varian produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductVariantPrice $productVariantPrice)
    {
        try {
            return new ProductVariantPriceResource($productVariantPrice);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data harga varian produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductVariantPrice $productVariantPrice)
    {
        try {
            $validatedData = $request->validate([
                'produk_id' => 'sometimes|required|exists:tb_produk,id',
                'gambar' => 'nullable|string|max:255',
                'harga' => 'sometimes|required|numeric|min:0',
                'stok' => 'sometimes|required|integer|min:0',
                'kode_varian' => 'nullable|string|max:255',
            ]);

            $productVariantPrice->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Harga varian produk berhasil diperbarui',
                'data' => new ProductVariantPriceResource($productVariantPrice)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui harga varian produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductVariantPrice $productVariantPrice)
    {
        try {
            $productVariantPrice->delete();

            return response()->json([
                'success' => true,
                'message' => 'Harga varian produk berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus harga varian produk: ' . $e->getMessage()
            ], 500);
        }
    }
}