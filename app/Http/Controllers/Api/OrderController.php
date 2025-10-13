<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orders = Order::paginate(15);
            return OrderResource::collection($orders);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pesanan: ' . $e->getMessage()
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
                'nomor_pesanan' => 'required|string|unique:pesanan,nomor_pesanan',
                'id_customer' => 'required|exists:users,id',
                'id_alamat_pengiriman' => 'required|exists:alamat,id',
                'status_pesanan' => 'required|in:KERANJANG,MENUNGGU_PEMBAYARAN,PEMBAYARAN_VERIFIKASI,SIAP_DIKIRIM,DIKIRIM,SELESAI,DIBATALKAN,PENGEMBALIAN,KOMPLAIN',
                'status_pembayaran' => 'required|in:BELUM_BAYAR,MENUNGGU_VERIFIKASI,TERBAYAR,KADALUARSA,GAGAL',
                'total_items' => 'required|integer|min:0',
                'total_berat' => 'required|numeric|min:0',
                'subtotal_produk' => 'required|numeric|min:0',
                'total_diskon_produk' => 'required|numeric|min:0',
                'total_ongkir' => 'required|numeric|min:0',
                'total_biaya_layanan' => 'required|numeric|min:0',
                'total_pajak' => 'required|numeric|min:0',
                'total_pembayaran' => 'required|numeric|min:0',
                'metode_pembayaran' => 'nullable|string|max:255',
                'bank_pembayaran' => 'nullable|string|max:255',
                'va_number' => 'nullable|string|max:255',
                'deadline_pembayaran' => 'nullable|date',
                'tanggal_dibayar' => 'nullable|date',
                'no_resi' => 'nullable|string|max:255',
                'catatan_pesanan' => 'nullable|string|max:500',
                'tanggal_pengiriman' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date',
                'tanggal_dibatalkan' => 'nullable|date',
                'alasan_pembatalan' => 'nullable|string|max:500',
            ]);

            $order = Order::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'data' => new OrderResource($order)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        try {
            return new OrderResource($order);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        try {
            $validatedData = $request->validate([
                'nomor_pesanan' => 'sometimes|required|string|unique:pesanan,nomor_pesanan,' . $order->id,
                'id_customer' => 'sometimes|required|exists:users,id',
                'id_alamat_pengiriman' => 'sometimes|required|exists:alamat,id',
                'status_pesanan' => 'sometimes|required|in:KERANJANG,MENUNGGU_PEMBAYARAN,PEMBAYARAN_VERIFIKASI,SIAP_DIKIRIM,DIKIRIM,SELESAI,DIBATALKAN,PENGEMBALIAN,KOMPLAIN',
                'status_pembayaran' => 'sometimes|required|in:BELUM_BAYAR,MENUNGGU_VERIFIKASI,TERBAYAR,KADALUARSA,GAGAL',
                'total_items' => 'sometimes|required|integer|min:0',
                'total_berat' => 'sometimes|required|numeric|min:0',
                'subtotal_produk' => 'sometimes|required|numeric|min:0',
                'total_diskon_produk' => 'sometimes|required|numeric|min:0',
                'total_ongkir' => 'sometimes|required|numeric|min:0',
                'total_biaya_layanan' => 'sometimes|required|numeric|min:0',
                'total_pajak' => 'sometimes|required|numeric|min:0',
                'total_pembayaran' => 'sometimes|required|numeric|min:0',
                'metode_pembayaran' => 'nullable|string|max:255',
                'bank_pembayaran' => 'nullable|string|max:255',
                'va_number' => 'nullable|string|max:255',
                'deadline_pembayaran' => 'nullable|date',
                'tanggal_dibayar' => 'nullable|date',
                'no_resi' => 'nullable|string|max:255',
                'catatan_pesanan' => 'nullable|string|max:500',
                'tanggal_pengiriman' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date',
                'tanggal_dibatalkan' => 'nullable|date',
                'alasan_pembatalan' => 'nullable|string|max:500',
            ]);

            $order->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil diperbarui',
                'data' => new OrderResource($order)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
}