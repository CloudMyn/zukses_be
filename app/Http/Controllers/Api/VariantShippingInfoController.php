<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VariantShippingInfo;
use App\Http\Resources\VariantShippingInfoResource;
use Illuminate\Http\Request;

class VariantShippingInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $variantShippingInfos = VariantShippingInfo::paginate(15);
            return VariantShippingInfoResource::collection($variantShippingInfos);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data informasi pengiriman varian: ' . $e->getMessage()
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
                'berat' => 'required|numeric|min:0',
                'panjang' => 'required|numeric|min:0',
                'lebar' => 'required|numeric|min:0',
                'tinggi' => 'required|numeric|min:0',
            ]);

            $variantShippingInfo = VariantShippingInfo::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Informasi pengiriman varian berhasil dibuat',
                'data' => new VariantShippingInfoResource($variantShippingInfo)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat informasi pengiriman varian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(VariantShippingInfo $variantShippingInfo)
    {
        try {
            return new VariantShippingInfoResource($variantShippingInfo);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data informasi pengiriman varian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VariantShippingInfo $variantShippingInfo)
    {
        try {
            $validatedData = $request->validate([
                'harga_varian_id' => 'sometimes|required|exists:harga_varian_produk,id',
                'berat' => 'sometimes|required|numeric|min:0',
                'panjang' => 'sometimes|required|numeric|min:0',
                'lebar' => 'sometimes|required|numeric|min:0',
                'tinggi' => 'sometimes|required|numeric|min:0',
            ]);

            $variantShippingInfo->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Informasi pengiriman varian berhasil diperbarui',
                'data' => new VariantShippingInfoResource($variantShippingInfo)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui informasi pengiriman varian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VariantShippingInfo $variantShippingInfo)
    {
        try {
            $variantShippingInfo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Informasi pengiriman varian berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus informasi pengiriman varian: ' . $e->getMessage()
            ], 500);
        }
    }
}