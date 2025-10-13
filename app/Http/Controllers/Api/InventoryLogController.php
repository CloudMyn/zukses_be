<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryLog;
use App\Http\Resources\InventoryLogResource;
use Illuminate\Http\Request;

class InventoryLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $inventoryLogs = InventoryLog::paginate(15);
            return InventoryLogResource::collection($inventoryLogs);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data log inventori: ' . $e->getMessage()
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
                'id_harga_varian' => 'nullable|exists:harga_varian_produk,id',
                'tipe_transaksi' => 'required|in:MASUK,KELUAR,PENYESUAIAN,RUSAK,KEMBALI',
                'jumlah_transaksi' => 'required|integer',
                'stok_sebelum' => 'required|integer|min:0',
                'stok_sesudah' => 'required|integer|min:0',
                'alasan_transaksi' => 'nullable|string|max:255',
                'id_operator' => 'required|exists:users,id',
                'catatan_tambahan' => 'nullable|string',
            ]);

            $inventoryLog = InventoryLog::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Log inventori berhasil dibuat',
                'data' => new InventoryLogResource($inventoryLog)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat log inventori: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryLog $inventoryLog)
    {
        try {
            return new InventoryLogResource($inventoryLog);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data log inventori: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InventoryLog $inventoryLog)
    {
        try {
            $validatedData = $request->validate([
                'id_produk' => 'sometimes|required|exists:tb_produk,id',
                'id_harga_varian' => 'nullable|exists:harga_varian_produk,id',
                'tipe_transaksi' => 'sometimes|required|in:MASUK,KELUAR,PENYESUAIAN,RUSAK,KEMBALI',
                'jumlah_transaksi' => 'sometimes|required|integer',
                'stok_sebelum' => 'sometimes|required|integer|min:0',
                'stok_sesudah' => 'sometimes|required|integer|min:0',
                'alasan_transaksi' => 'nullable|string|max:255',
                'id_operator' => 'sometimes|required|exists:users,id',
                'catatan_tambahan' => 'nullable|string',
            ]);

            $inventoryLog->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Log inventori berhasil diperbarui',
                'data' => new InventoryLogResource($inventoryLog)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui log inventori: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryLog $inventoryLog)
    {
        try {
            $inventoryLog->delete();

            return response()->json([
                'success' => true,
                'message' => 'Log inventori berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus log inventori: ' . $e->getMessage()
            ], 500);
        }
    }
}