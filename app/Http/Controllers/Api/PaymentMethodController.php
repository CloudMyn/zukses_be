<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Http\Resources\PaymentMethodResource;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $paymentMethods = PaymentMethod::paginate(15);
            return PaymentMethodResource::collection($paymentMethods);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data metode pembayaran: ' . $e->getMessage()
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
                'nama_pembayaran' => 'required|string|max:255',
                'tipe_pembayaran' => 'required|in:TRANSFER_BANK,E_WALLET,VIRTUAL_ACCOUNT,CREDIT_CARD,COD,QRIS',
                'provider_pembayaran' => 'required|string|max:255',
                'logo_pembayaran' => 'nullable|string|max:255',
                'deskripsi_pembayaran' => 'nullable|string',
                'biaya_admin_percent' => 'required|numeric|min:0|max:100',
                'biaya_admin_fixed' => 'required|numeric|min:0',
                'minimum_pembayaran' => 'required|numeric|min:0',
                'maksimum_pembayaran' => 'nullable|numeric|min:0|gte:minimum_pembayaran',
                'is_aktif' => 'required|boolean',
                'urutan_tampilan' => 'required|integer|min:0',
            ]);

            $paymentMethod = PaymentMethod::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Metode pembayaran berhasil dibuat',
                'data' => new PaymentMethodResource($paymentMethod)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat metode pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        try {
            return new PaymentMethodResource($paymentMethod);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data metode pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        try {
            $validatedData = $request->validate([
                'nama_pembayaran' => 'sometimes|required|string|max:255',
                'tipe_pembayaran' => 'sometimes|required|in:TRANSFER_BANK,E_WALLET,VIRTUAL_ACCOUNT,CREDIT_CARD,COD,QRIS',
                'provider_pembayaran' => 'sometimes|required|string|max:255',
                'logo_pembayaran' => 'nullable|string|max:255',
                'deskripsi_pembayaran' => 'nullable|string',
                'biaya_admin_percent' => 'sometimes|required|numeric|min:0|max:100',
                'biaya_admin_fixed' => 'sometimes|required|numeric|min:0',
                'minimum_pembayaran' => 'sometimes|required|numeric|min:0',
                'maksimum_pembayaran' => 'nullable|numeric|min:0|gte:minimum_pembayaran',
                'is_aktif' => 'sometimes|required|boolean',
                'urutan_tampilan' => 'sometimes|required|integer|min:0',
            ]);

            $paymentMethod->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Metode pembayaran berhasil diperbarui',
                'data' => new PaymentMethodResource($paymentMethod)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui metode pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        try {
            $paymentMethod->delete();

            return response()->json([
                'success' => true,
                'message' => 'Metode pembayaran berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus metode pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
}