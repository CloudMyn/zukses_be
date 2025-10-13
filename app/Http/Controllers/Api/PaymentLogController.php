<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentLog;
use App\Http\Resources\PaymentLogResource;
use Illuminate\Http\Request;

class PaymentLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $paymentLogs = PaymentLog::paginate(15);
            return PaymentLogResource::collection($paymentLogs);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data log pembayaran: ' . $e->getMessage()
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
                'id_transaksi_pembayaran' => 'required|exists:transaksi_pembayaran,id',
                'tipe_log' => 'required|in:REQUEST,RESPONSE,CALLBACK,ERROR',
                'konten_log' => 'required|string',
                'ip_address' => 'nullable|string|max:45',
                'user_agent' => 'nullable|string|max:500',
            ]);

            $paymentLog = PaymentLog::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Log pembayaran berhasil dibuat',
                'data' => new PaymentLogResource($paymentLog)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat log pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentLog $paymentLog)
    {
        try {
            return new PaymentLogResource($paymentLog);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data log pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentLog $paymentLog)
    {
        try {
            $validatedData = $request->validate([
                'id_transaksi_pembayaran' => 'sometimes|required|exists:transaksi_pembayaran,id',
                'tipe_log' => 'sometimes|required|in:REQUEST,RESPONSE,CALLBACK,ERROR',
                'konten_log' => 'sometimes|required|string',
                'ip_address' => 'nullable|string|max:45',
                'user_agent' => 'nullable|string|max:500',
            ]);

            $paymentLog->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Log pembayaran berhasil diperbarui',
                'data' => new PaymentLogResource($paymentLog)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui log pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentLog $paymentLog)
    {
        try {
            $paymentLog->delete();

            return response()->json([
                'success' => true,
                'message' => 'Log pembayaran berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus log pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
}