<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductVariantValue;
use App\Http\Resources\ProductVariantValueResource;
use Illuminate\Http\Request;

class ProductVariantValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $productVariantValues = ProductVariantValue::paginate(15);
            return ProductVariantValueResource::collection($productVariantValues);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data nilai varian produk: ' . $e->getMessage()
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
                'varian_id' => 'required|exists:varian_produk,id',
                'nilai' => 'required|string|max:255',
                'urutan' => 'required|integer|min:0',
            ]);

            $productVariantValue = ProductVariantValue::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Nilai varian produk berhasil dibuat',
                'data' => new ProductVariantValueResource($productVariantValue)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat nilai varian produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductVariantValue $productVariantValue)
    {
        try {
            return new ProductVariantValueResource($productVariantValue);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data nilai varian produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductVariantValue $productVariantValue)
    {
        try {
            $validatedData = $request->validate([
                'varian_id' => 'sometimes|required|exists:varian_produk,id',
                'nilai' => 'sometimes|required|string|max:255',
                'urutan' => 'sometimes|required|integer|min:0',
            ]);

            $productVariantValue->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Nilai varian produk berhasil diperbarui',
                'data' => new ProductVariantValueResource($productVariantValue)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui nilai varian produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductVariantValue $productVariantValue)
    {
        try {
            $productVariantValue->delete();

            return response()->json([
                'success' => true,
                'message' => 'Nilai varian produk berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus nilai varian produk: ' . $e->getMessage()
            ], 500);
        }
    }
}