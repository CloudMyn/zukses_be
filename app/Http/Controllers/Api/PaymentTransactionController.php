<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Http\Resources\PaymentTransactionResource;
use Illuminate\Http\Request;

class PaymentTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $paymentTransactions = PaymentTransaction::paginate(15);
            return PaymentTransactionResource::collection($paymentTransactions);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data transaksi pembayaran: ' . $e->getMessage()
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
                'id_metode_pembayaran' => 'required|exists:metode_pembayaran,id',
                'referensi_id' => 'required|string|unique:transaksi_pembayaran,referensi_id',
                'jumlah_pembayaran' => 'required|numeric|min:0',
                'status_transaksi' => 'required|in:MENUNGGU,BERHASIL,GAGAL,KADALUARSA',
                'channel_pembayaran' => 'nullable|string|max:255',
                'va_number' => 'nullable|string|max:255',
                'qr_code' => 'nullable|string|max:500',
                'deep_link' => 'nullable|string|max:500',
                'tanggal_kadaluarsa' => 'nullable|date',
                'tanggal_bayar' => 'nullable|date',
                'response_gateway' => 'nullable|json',
            ]);

            $paymentTransaction = PaymentTransaction::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi pembayaran berhasil dibuat',
                'data' => new PaymentTransactionResource($paymentTransaction)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat transaksi pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentTransaction $paymentTransaction)
    {
        try {
            return new PaymentTransactionResource($paymentTransaction);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data transaksi pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentTransaction $paymentTransaction)
    {
        try {
            $validatedData = $request->validate([
                'id_pesanan' => 'sometimes|required|exists:pesanan,id',
                'id_metode_pembayaran' => 'sometimes|required|exists:metode_pembayaran,id',
                'referensi_id' => 'sometimes|required|string|unique:transaksi_pembayaran,referensi_id,' . $paymentTransaction->id,
                'jumlah_pembayaran' => 'sometimes|required|numeric|min:0',
                'status_transaksi' => 'sometimes|required|in:MENUNGGU,BERHASIL,GAGAL,KADALUARSA',
                'channel_pembayaran' => 'nullable|string|max:255',
                'va_number' => 'nullable|string|max:255',
                'qr_code' => 'nullable|string|max:500',
                'deep_link' => 'nullable|string|max:500',
                'tanggal_kadaluarsa' => 'nullable|date',
                'tanggal_bayar' => 'nullable|date',
                'response_gateway' => 'nullable|json',
            ]);

            $paymentTransaction->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi pembayaran berhasil diperbarui',
                'data' => new PaymentTransactionResource($paymentTransaction)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui transaksi pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentTransaction $paymentTransaction)
    {
        try {
            $paymentTransaction->delete();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi pembayaran berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus transaksi pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
}