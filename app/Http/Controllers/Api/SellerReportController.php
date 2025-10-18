<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SellerReport;
use App\Http\Resources\SellerReportResource;
use Illuminate\Http\Request;

class SellerReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $sellerReports = SellerReport::paginate(15);
            return SellerReportResource::collection($sellerReports);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data laporan penjual: ' . $e->getMessage()
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
                'id_seller' => 'required|exists:tb_penjual,id',
                'tipe_laporan' => 'required|in:HARIAN,MINGGUAN,BULANAN,TAHUNAN',
                'periode_laporan' => 'required|date',
                'total_pesanan' => 'required|integer|min:0',
                'total_penjualan' => 'required|numeric|min:0',
                'total_pendapatan' => 'required|numeric|min:0',
                'total_ongkir' => 'required|numeric|min:0',
                'total_komisi_platform' => 'required|numeric|min:0',
                'total_bersih' => 'required|numeric|min:0',
                'produk_terlaris' => 'nullable|json',
                'pembelian_terbanyak' => 'nullable|json',
                'rating_rata_rata' => 'required|numeric|min:0|max:5',
                'dibuat_pada' => 'nullable|date',
            ]);

            $sellerReport = SellerReport::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Laporan penjual berhasil dibuat',
                'data' => new SellerReportResource($sellerReport)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat laporan penjual: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SellerReport $sellerReport)
    {
        try {
            return new SellerReportResource($sellerReport);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data laporan penjual: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SellerReport $sellerReport)
    {
        try {
            $validatedData = $request->validate([
                'id_seller' => 'sometimes|required|exists:tb_penjual,id',
                'tipe_laporan' => 'sometimes|required|in:HARIAN,MINGGUAN,BULANAN,TAHUNAN',
                'periode_laporan' => 'sometimes|required|date',
                'total_pesanan' => 'sometimes|required|integer|min:0',
                'total_penjualan' => 'sometimes|required|numeric|min:0',
                'total_pendapatan' => 'sometimes|required|numeric|min:0',
                'total_ongkir' => 'sometimes|required|numeric|min:0',
                'total_komisi_platform' => 'sometimes|required|numeric|min:0',
                'total_bersih' => 'sometimes|required|numeric|min:0',
                'produk_terlaris' => 'nullable|json',
                'pembelian_terbanyak' => 'nullable|json',
                'rating_rata_rata' => 'sometimes|required|numeric|min:0|max:5',
                'dibuat_pada' => 'nullable|date',
            ]);

            $sellerReport->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Laporan penjual berhasil diperbarui',
                'data' => new SellerReportResource($sellerReport)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui laporan penjual: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SellerReport $sellerReport)
    {
        try {
            $sellerReport->delete();

            return response()->json([
                'success' => true,
                'message' => 'Laporan penjual berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus laporan penjual: ' . $e->getMessage()
            ], 500);
        }
    }
}