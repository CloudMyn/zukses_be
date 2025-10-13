<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductShippingInfo;
use App\Http\Resources\ProductShippingInfoResource;
use Illuminate\Http\Request;

class ProductShippingInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $productShippingInfos = ProductShippingInfo::paginate(15);
            return ProductShippingInfoResource::collection($productShippingInfos);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data informasi pengiriman produk: ' . $e->getMessage()
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
                'id_kota_asal' => 'required|exists:master_kota,id',
                'nama_kota_asal' => 'required|string|max:255',
                'estimasi_pengiriman' => 'nullable|json',
                'berat_pengiriman' => 'required|numeric|min:0',
                'dimensi_pengiriman' => 'nullable|json',
                'biaya_pengemasan' => 'required|numeric|min:0',
                'is_gratis_ongkir' => 'required|boolean',
            ]);

            $productShippingInfo = ProductShippingInfo::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Informasi pengiriman produk berhasil dibuat',
                'data' => new ProductShippingInfoResource($productShippingInfo)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat informasi pengiriman produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductShippingInfo $productShippingInfo)
    {
        try {
            return new ProductShippingInfoResource($productShippingInfo);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data informasi pengiriman produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductShippingInfo $productShippingInfo)
    {
        try {
            $validatedData = $request->validate([
                'id_produk' => 'sometimes|required|exists:tb_produk,id',
                'id_kota_asal' => 'sometimes|required|exists:master_kota,id',
                'nama_kota_asal' => 'sometimes|required|string|max:255',
                'estimasi_pengiriman' => 'nullable|json',
                'berat_pengiriman' => 'sometimes|required|numeric|min:0',
                'dimensi_pengiriman' => 'nullable|json',
                'biaya_pengemasan' => 'sometimes|required|numeric|min:0',
                'is_gratis_ongkir' => 'sometimes|required|boolean',
            ]);

            $productShippingInfo->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Informasi pengiriman produk berhasil diperbarui',
                'data' => new ProductShippingInfoResource($productShippingInfo)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui informasi pengiriman produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductShippingInfo $productShippingInfo)
    {
        try {
            $productShippingInfo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Informasi pengiriman produk berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus informasi pengiriman produk: ' . $e->getMessage()
            ], 500);
        }
    }
}