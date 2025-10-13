<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SalesReport;
use App\Http\Resources\SalesReportResource;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $salesReports = SalesReport::paginate(15);
            return SalesReportResource::collection($salesReports);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data laporan penjualan: ' . $e->getMessage()
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
                'tipe_laporan' => 'required|in:GLOBAL,KATEGORI,PROVINSI,METODE_PEMBAYARAN',
                'periode_laporan' => 'required|date',
                'data_laporan' => 'nullable|json',
                'total_transaksi' => 'required|integer|min:0',
                'total_nilai_transaksi' => 'required|numeric|min:0',
                'dibuat_pada' => 'nullable|date',
            ]);

            $salesReport = SalesReport::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Laporan penjualan berhasil dibuat',
                'data' => new SalesReportResource($salesReport)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat laporan penjualan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesReport $salesReport)
    {
        try {
            return new SalesReportResource($salesReport);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data laporan penjualan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesReport $salesReport)
    {
        try {
            $validatedData = $request->validate([
                'tipe_laporan' => 'sometimes|required|in:GLOBAL,KATEGORI,PROVINSI,METODE_PEMBAYARAN',
                'periode_laporan' => 'sometimes|required|date',
                'data_laporan' => 'nullable|json',
                'total_transaksi' => 'sometimes|required|integer|min:0',
                'total_nilai_transaksi' => 'sometimes|required|numeric|min:0',
                'dibuat_pada' => 'nullable|date',
            ]);

            $salesReport->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Laporan penjualan berhasil diperbarui',
                'data' => new SalesReportResource($salesReport)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui laporan penjualan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesReport $salesReport)
    {
        try {
            $salesReport->delete();

            return response()->json([
                'success' => true,
                'message' => 'Laporan penjualan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus laporan penjualan: ' . $e->getMessage()
            ], 500);
        }
    }
}