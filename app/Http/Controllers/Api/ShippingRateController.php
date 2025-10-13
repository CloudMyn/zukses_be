<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use App\Http\Resources\ShippingRateResource;
use Illuminate\Http\Request;

class ShippingRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $shippingRates = ShippingRate::paginate(15);
            return ShippingRateResource::collection($shippingRates);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data tarif pengiriman: ' . $e->getMessage()
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
                'id_kurir' => 'required|exists:metode_pengiriman,id',
                'id_kota_asal' => 'required|exists:master_kota,id',
                'id_kota_tujuan' => 'required|exists:master_kota,id',
                'berat_minimal' => 'required|numeric|min:0',
                'berat_maksimal' => 'required|numeric|min:0|gt:berat_minimal',
                'harga_ongkir' => 'required|numeric|min:0',
                'estimasi_pengiriman' => 'nullable|integer|min:0',
                'is_aktif' => 'required|boolean',
            ]);

            $shippingRate = ShippingRate::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Tarif pengiriman berhasil dibuat',
                'data' => new ShippingRateResource($shippingRate)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat tarif pengiriman: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingRate $shippingRate)
    {
        try {
            return new ShippingRateResource($shippingRate);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data tarif pengiriman: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShippingRate $shippingRate)
    {
        try {
            $validatedData = $request->validate([
                'id_kurir' => 'sometimes|required|exists:metode_pengiriman,id',
                'id_kota_asal' => 'sometimes|required|exists:master_kota,id',
                'id_kota_tujuan' => 'sometimes|required|exists:master_kota,id',
                'berat_minimal' => 'sometimes|required|numeric|min:0',
                'berat_maksimal' => 'sometimes|required|numeric|min:0|gt:berat_minimal',
                'harga_ongkir' => 'sometimes|required|numeric|min:0',
                'estimasi_pengiriman' => 'nullable|integer|min:0',
                'is_aktif' => 'sometimes|required|boolean',
            ]);

            $shippingRate->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Tarif pengiriman berhasil diperbarui',
                'data' => new ShippingRateResource($shippingRate)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui tarif pengiriman: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingRate $shippingRate)
    {
        try {
            $shippingRate->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tarif pengiriman berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus tarif pengiriman: ' . $e->getMessage()
            ], 500);
        }
    }
}