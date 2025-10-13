<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Http\Resources\SellerResource;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $sellers = Seller::paginate(15);
            return SellerResource::collection($sellers);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data penjual: ' . $e->getMessage()
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
                'id_user' => 'required|exists:users,id',
                'nama_toko' => 'required|string|unique:penjual,nama_toko',
                'slug_toko' => 'required|string|unique:penjual,slug_toko',
                'deskripsi_toko' => 'nullable|string',
                'logo_toko' => 'nullable|string',
                'banner_toko' => 'nullable|string',
                'nomor_ktp' => 'required|string|unique:penjual,nomor_ktp',
                'foto_ktp' => 'nullable|string',
                'nomor_npwp' => 'nullable|string|unique:penjual,nomor_npwp',
                'foto_npwp' => 'nullable|string',
                'jenis_usaha' => 'required|in:INDIVIDU,PERUSAHAAN',
                'status_verifikasi' => 'required|in:MENUNGGU,TERVERIFIKASI,DITOLAK,PERLU_DIREVISI',
                'tanggal_verifikasi' => 'nullable|date',
                'id_verifikator' => 'nullable|exists:users,id',
                'catatan_verifikasi' => 'nullable|string',
                'rating_toko' => 'nullable|numeric|min:0|max:5',
                'total_penjualan' => 'nullable|integer|min:0',
            ]);

            $seller = Seller::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Penjual berhasil dibuat',
                'data' => new SellerResource($seller)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat penjual: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Seller $seller)
    {
        try {
            return new SellerResource($seller);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data penjual: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seller $seller)
    {
        try {
            $validatedData = $request->validate([
                'id_user' => 'sometimes|required|exists:users,id',
                'nama_toko' => 'sometimes|required|string|unique:penjual,nama_toko,' . $seller->id,
                'slug_toko' => 'sometimes|required|string|unique:penjual,slug_toko,' . $seller->id,
                'deskripsi_toko' => 'nullable|string',
                'logo_toko' => 'nullable|string',
                'banner_toko' => 'nullable|string',
                'nomor_ktp' => 'sometimes|required|string|unique:penjual,nomor_ktp,' . $seller->id,
                'foto_ktp' => 'nullable|string',
                'nomor_npwp' => 'nullable|string|unique:penjual,nomor_npwp,' . $seller->id,
                'foto_npwp' => 'nullable|string',
                'jenis_usaha' => 'sometimes|required|in:INDIVIDU,PERUSAHAAN',
                'status_verifikasi' => 'sometimes|required|in:MENUNGGU,TERVERIFIKASI,DITOLAK,PERLU_DIREVISI',
                'tanggal_verifikasi' => 'nullable|date',
                'id_verifikator' => 'nullable|exists:users,id',
                'catatan_verifikasi' => 'nullable|string',
                'rating_toko' => 'nullable|numeric|min:0|max:5',
                'total_penjualan' => 'nullable|integer|min:0',
            ]);

            $seller->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Penjual berhasil diperbarui',
                'data' => new SellerResource($seller)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui penjual: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seller $seller)
    {
        try {
            $seller->delete();

            return response()->json([
                'success' => true,
                'message' => 'Penjual berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus penjual: ' . $e->getMessage()
            ], 500);
        }
    }
}