<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderStatusHistory;
use App\Http\Resources\OrderStatusHistoryResource;
use Illuminate\Http\Request;

class OrderStatusHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orderStatusHistories = OrderStatusHistory::paginate(15);
            return OrderStatusHistoryResource::collection($orderStatusHistories);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data riwayat status pesanan: ' . $e->getMessage()
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
                'status_sebelumnya' => 'nullable|string|max:255',
                'status_sekarang' => 'required|string|max:255',
                'catatan_status' => 'nullable|string|max:500',
                'id_pengubah' => 'nullable|exists:users,id',
            ]);

            $orderStatusHistory = OrderStatusHistory::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Riwayat status pesanan berhasil dibuat',
                'data' => new OrderStatusHistoryResource($orderStatusHistory)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat riwayat status pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderStatusHistory $orderStatusHistory)
    {
        try {
            return new OrderStatusHistoryResource($orderStatusHistory);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data riwayat status pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderStatusHistory $orderStatusHistory)
    {
        try {
            $validatedData = $request->validate([
                'id_pesanan' => 'sometimes|required|exists:pesanan,id',
                'status_sebelumnya' => 'nullable|string|max:255',
                'status_sekarang' => 'sometimes|required|string|max:255',
                'catatan_status' => 'nullable|string|max:500',
                'id_pengubah' => 'nullable|exists:users,id',
            ]);

            $orderStatusHistory->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Riwayat status pesanan berhasil diperbarui',
                'data' => new OrderStatusHistoryResource($orderStatusHistory)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui riwayat status pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderStatusHistory $orderStatusHistory)
    {
        try {
            $orderStatusHistory->delete();

            return response()->json([
                'success' => true,
                'message' => 'Riwayat status pesanan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus riwayat status pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
}