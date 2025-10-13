<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VariantPriceComposition;
use App\Http\Resources\VariantPriceCompositionResource;
use Illuminate\Http\Request;

class VariantPriceCompositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $variantPriceCompositions = VariantPriceComposition::paginate(15);
            return VariantPriceCompositionResource::collection($variantPriceCompositions);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data komposisi harga varian: ' . $e->getMessage()
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
                'harga_varian_id' => 'required|exists:harga_varian_produk,id',
                'nilai_varian_id' => 'required|exists:nilai_varian_produk,id',
            ]);

            $variantPriceComposition = VariantPriceComposition::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Komposisi harga varian berhasil dibuat',
                'data' => new VariantPriceCompositionResource($variantPriceComposition)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat komposisi harga varian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(VariantPriceComposition $variantPriceComposition)
    {
        try {
            return new VariantPriceCompositionResource($variantPriceComposition);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data komposisi harga varian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VariantPriceComposition $variantPriceComposition)
    {
        try {
            $validatedData = $request->validate([
                'harga_varian_id' => 'sometimes|required|exists:harga_varian_produk,id',
                'nilai_varian_id' => 'sometimes|required|exists:nilai_varian_produk,id',
            ]);

            $variantPriceComposition->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Komposisi harga varian berhasil diperbarui',
                'data' => new VariantPriceCompositionResource($variantPriceComposition)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui komposisi harga varian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VariantPriceComposition $variantPriceComposition)
    {
        try {
            $variantPriceComposition->delete();

            return response()->json([
                'success' => true,
                'message' => 'Komposisi harga varian berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus komposisi harga varian: ' . $e->getMessage()
            ], 500);
        }
    }
}