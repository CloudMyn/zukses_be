<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderShipping;
use App\Http\Resources\OrderShippingResource;
use Illuminate\Http\Request;

class OrderShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orderShippings = OrderShipping::paginate(15);
            return OrderShippingResource::collection($orderShippings);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pengiriman pesanan: ' . $e->getMessage()
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
                'id_pesanan' => 'required|exists:pesanan,id',
                'id_kurir' => 'required|exists:metode_pengiriman,id',
                'nama_kurir' => 'required|string|max:255',
                'tipe_layanan' => 'required|string|max:255',
                'no_resi' => 'nullable|string|max:255',
                'status_pengiriman' => 'required|in:MENUNGGU,DIKEMAS,DIJEMPUT,DALAM_PERJALANAN,SAMPAI,GAGAL',
                'estimasi_pengiriman' => 'nullable|integer|min:0',
                'biaya_pengiriman' => 'required|numeric|min:0',
                'biaya_asuransi' => 'required|numeric|min:0',
                'biaya_lainnya' => 'required|numeric|min:0',
                'alamat_pengiriman' => 'required|json',
                'catatan_pengiriman' => 'nullable|string|max:500',
                'link_tracking' => 'nullable|string|max:255',
            ]);

            $orderShipping = OrderShipping::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Pengiriman pesanan berhasil dibuat',
                'data' => new OrderShippingResource($orderShipping)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat pengiriman pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderShipping $orderShipping)
    {
        try {
            return new OrderShippingResource($orderShipping);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pengiriman pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderShipping $orderShipping)
    {
        try {
            $validatedData = $request->validate([
                'id_pesanan' => 'sometimes|required|exists:pesanan,id',
                'id_kurir' => 'sometimes|required|exists:metode_pengiriman,id',
                'nama_kurir' => 'sometimes|required|string|max:255',
                'tipe_layanan' => 'sometimes|required|string|max:255',
                'no_resi' => 'nullable|string|max:255',
                'status_pengiriman' => 'sometimes|required|in:MENUNGGU,DIKEMAS,DIJEMPUT,DALAM_PERJALANAN,SAMPAI,GAGAL',
                'estimasi_pengiriman' => 'nullable|integer|min:0',
                'biaya_pengiriman' => 'sometimes|required|numeric|min:0',
                'biaya_asuransi' => 'sometimes|required|numeric|min:0',
                'biaya_lainnya' => 'sometimes|required|numeric|min:0',
                'alamat_pengiriman' => 'sometimes|required|json',
                'catatan_pengiriman' => 'nullable|string|max:500',
                'link_tracking' => 'nullable|string|max:255',
            ]);

            $orderShipping->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Pengiriman pesanan berhasil diperbarui',
                'data' => new OrderShippingResource($orderShipping)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui pengiriman pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderShipping $orderShipping)
    {
        try {
            $orderShipping->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pengiriman pesanan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus pengiriman pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
}