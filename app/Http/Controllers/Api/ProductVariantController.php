<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Http\Resources\ProductVariantResource;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $productVariants = ProductVariant::paginate(15);
            return ProductVariantResource::collection($productVariants);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data varian produk: ' . $e->getMessage()
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
                'nama_varian' => 'required|string|max:255',
                'urutan' => 'required|integer|min:0',
            ]);

            $productVariant = ProductVariant::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Varian produk berhasil dibuat',
                'data' => new ProductVariantResource($productVariant)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat varian produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductVariant $productVariant)
    {
        try {
            return new ProductVariantResource($productVariant);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data varian produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductVariant $productVariant)
    {
        try {
            $validatedData = $request->validate([
                'produk_id' => 'sometimes|required|exists:tb_produk,id',
                'nama_varian' => 'sometimes|required|string|max:255',
                'urutan' => 'sometimes|required|integer|min:0',
            ]);

            $productVariant->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Varian produk berhasil diperbarui',
                'data' => new ProductVariantResource($productVariant)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui varian produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductVariant $productVariant)
    {
        try {
            $productVariant->delete();

            return response()->json([
                'success' => true,
                'message' => 'Varian produk berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus varian produk: ' . $e->getMessage()
            ], 500);
        }
    }
}