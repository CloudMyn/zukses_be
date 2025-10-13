<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use App\Http\Resources\ShippingMethodResource;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $shippingMethods = ShippingMethod::paginate(15);
            return ShippingMethodResource::collection($shippingMethods);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data metode pengiriman: ' . $e->getMessage()
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
                'nama_kurir' => 'required|string|max:255',
                'tipe_layanan' => 'required|string|max:255',
                'deskripsi_layanan' => 'nullable|string',
                'logo_kurir' => 'nullable|string|max:255',
                'is_aktif' => 'required|boolean',
                'is_cargo' => 'required|boolean',
                'estimasi_pengiriman_min' => 'nullable|integer|min:0',
                'estimasi_pengiriman_max' => 'nullable|integer|min:0',
            ]);

            $shippingMethod = ShippingMethod::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Metode pengiriman berhasil dibuat',
                'data' => new ShippingMethodResource($shippingMethod)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat metode pengiriman: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingMethod $shippingMethod)
    {
        try {
            return new ShippingMethodResource($shippingMethod);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data metode pengiriman: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShippingMethod $shippingMethod)
    {
        try {
            $validatedData = $request->validate([
                'nama_kurir' => 'sometimes|required|string|max:255',
                'tipe_layanan' => 'sometimes|required|string|max:255',
                'deskripsi_layanan' => 'nullable|string',
                'logo_kurir' => 'nullable|string|max:255',
                'is_aktif' => 'sometimes|required|boolean',
                'is_cargo' => 'sometimes|required|boolean',
                'estimasi_pengiriman_min' => 'nullable|integer|min:0',
                'estimasi_pengiriman_max' => 'nullable|integer|min:0|gte:estimasi_pengiriman_min',
            ]);

            $shippingMethod->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Metode pengiriman berhasil diperbarui',
                'data' => new ShippingMethodResource($shippingMethod)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui metode pengiriman: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingMethod $shippingMethod)
    {
        try {
            $shippingMethod->delete();

            return response()->json([
                'success' => true,
                'message' => 'Metode pengiriman berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus metode pengiriman: ' . $e->getMessage()
            ], 500);
        }
    }
}