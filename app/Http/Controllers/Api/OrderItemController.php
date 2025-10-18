<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Http\Resources\OrderItemResource;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orderItems = OrderItem::paginate(15);
            return OrderItemResource::collection($orderItems);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data item pesanan: ' . $e->getMessage()
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
                'id_seller' => 'required|exists:tb_penjual,id',
                'id_produk' => 'required|exists:tb_produk,id',
                'id_harga_varian' => 'nullable|exists:harga_varian_produk,id',
                'nama_produk' => 'required|string|max:255',
                'gambar_produk' => 'nullable|string|max:255',
                'sku_produk' => 'required|string|max:255',
                'atribut_varian' => 'nullable|json',
                'harga_satuan' => 'required|numeric|min:0',
                'jumlah_pesanan' => 'required|integer|min:1',
                'subtotal_harga' => 'required|numeric|min:0',
                'diskon_item' => 'required|numeric|min:0',
                'berat_item' => 'required|numeric|min:0',
                'status_item' => 'required|in:MENUNGGU,DIKEMAS,DIKIRIM,SELESAI,DIBATALKAN,DIKEMBALIKAN',
                'catatan_item' => 'nullable|string|max:500',
            ]);

            $orderItem = OrderItem::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Item pesanan berhasil dibuat',
                'data' => new OrderItemResource($orderItem)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat item pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderItem $orderItem)
    {
        try {
            return new OrderItemResource($orderItem);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data item pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderItem $orderItem)
    {
        try {
            $validatedData = $request->validate([
                'id_pesanan' => 'sometimes|required|exists:pesanan,id',
                'id_seller' => 'sometimes|required|exists:tb_penjual,id',
                'id_produk' => 'sometimes|required|exists:tb_produk,id',
                'id_harga_varian' => 'nullable|exists:harga_varian_produk,id',
                'nama_produk' => 'sometimes|required|string|max:255',
                'gambar_produk' => 'nullable|string|max:255',
                'sku_produk' => 'sometimes|required|string|max:255',
                'atribut_varian' => 'nullable|json',
                'harga_satuan' => 'sometimes|required|numeric|min:0',
                'jumlah_pesanan' => 'sometimes|required|integer|min:1',
                'subtotal_harga' => 'sometimes|required|numeric|min:0',
                'diskon_item' => 'sometimes|required|numeric|min:0',
                'berat_item' => 'sometimes|required|numeric|min:0',
                'status_item' => 'sometimes|required|in:MENUNGGU,DIKEMAS,DIKIRIM,SELESAI,DIBATALKAN,DIKEMBALIKAN',
                'catatan_item' => 'nullable|string|max:500',
            ]);

            $orderItem->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Item pesanan berhasil diperbarui',
                'data' => new OrderItemResource($orderItem)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui item pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderItem $orderItem)
    {
        try {
            $orderItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item pesanan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus item pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
}