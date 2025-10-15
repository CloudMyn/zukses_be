<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Http\Resources\SellerResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $sellers = Seller::with('user')->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'Data penjual berhasil diambil',
                'data' => SellerResource::collection($sellers),
                'pagination' => [
                    'current_page' => $sellers->currentPage(),
                    'last_page' => $sellers->lastPage(),
                    'per_page' => $sellers->perPage(),
                    'total' => $sellers->total()
                ]
            ], 200);
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
            // Use authenticated user's ID if not provided
            $userId = $request->id_user ?? Auth::id();

            $validatedData = $request->validate([
                'id_user' => 'sometimes|required|exists:users,id',
                'nama_toko' => 'required|string|unique:tb_penjual,nama_toko',
                'deskripsi' => 'nullable|string', // API-friendly field name
                'deskripsi_toko' => 'nullable|string',
                'logo_toko' => 'nullable|string',
                'banner_toko' => 'nullable|string',
                'alamat_toko' => 'nullable|string', // Additional field for API
                'nomor_ktp' => 'nullable|string|unique:tb_penjual,nomor_ktp',
                'foto_ktp' => 'nullable|string',
                'nomor_npwp' => 'nullable|string|unique:tb_penjual,nomor_npwp',
                'foto_npwp' => 'nullable|string',
                'jenis_usaha' => 'sometimes|required|in:INDIVIDU,PERUSAHAAN',
                'status_verifikasi' => 'sometimes|required|in:MENUNGGU,TERVERIFIKASI,DITOLAK,PERLU_DIREVISI',
                'tanggal_verifikasi' => 'nullable|date',
                'id_verifikator' => 'nullable|exists:users,id',
                'catatan_verifikasi' => 'nullable|string',
                'rating_toko' => 'nullable|numeric|min:0|max:5',
                'total_penjualan' => 'nullable|integer|min:0',
            ]);

            // Map API-friendly fields to database fields
            if (isset($validatedData['deskripsi'])) {
                $validatedData['deskripsi_toko'] = $validatedData['deskripsi'];
                unset($validatedData['deskripsi']);
            }

            // Generate slug if not provided
            if (!isset($validatedData['slug_toko'])) {
                $validatedData['slug_toko'] = Str::slug($validatedData['nama_toko']);
            }

            // Set default values for required fields if not provided
            if (!isset($validatedData['jenis_usaha'])) {
                $validatedData['jenis_usaha'] = 'INDIVIDU';
            }
            if (!isset($validatedData['status_verifikasi'])) {
                $validatedData['status_verifikasi'] = 'MENUNGGU';
            }

            // Set the user ID if not provided
            if (!isset($validatedData['id_user'])) {
                $validatedData['id_user'] = $userId;
            }

            $seller = Seller::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Penjual berhasil dibuat',
                'data' => new SellerResource($seller)
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
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
    public function show($id)
    {
        try {
            $seller = Seller::with('user')->find($id);

            if (!$seller) {
                return response()->json([
                    'success' => false,
                    'message' => 'Penjual tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data penjual berhasil diambil',
                'data' => new SellerResource($seller)
            ], 200);
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
    public function update(Request $request, $id)
    {
        try {
            $seller = Seller::find($id);

            if (!$seller) {
                return response()->json([
                    'success' => false,
                    'message' => 'Penjual tidak ditemukan'
                ], 404);
            }

            $validatedData = $request->validate([
                'id_user' => 'sometimes|required|exists:users,id',
                'nama_toko' => 'sometimes|required|string|unique:tb_penjual,nama_toko,' . $seller->id,
                'slug_toko' => 'sometimes|required|string|unique:tb_penjual,slug_toko,' . $seller->id,
                'deskripsi' => 'nullable|string', // API-friendly field name
                'deskripsi_toko' => 'nullable|string',
                'logo_toko' => 'nullable|string',
                'banner_toko' => 'nullable|string',
                'alamat_toko' => 'nullable|string', // Additional field for API
                'nomor_ktp' => 'sometimes|required|string|unique:tb_penjual,nomor_ktp,' . $seller->id,
                'foto_ktp' => 'nullable|string',
                'nomor_npwp' => 'nullable|string|unique:tb_penjual,nomor_npwp,' . $seller->id,
                'foto_npwp' => 'nullable|string',
                'jenis_usaha' => 'sometimes|required|in:INDIVIDU,PERUSAHAAN',
                'status_verifikasi' => 'sometimes|required|in:MENUNGGU,TERVERIFIKASI,DITOLAK,PERLU_DIREVISI',
                'tanggal_verifikasi' => 'nullable|date',
                'id_verifikator' => 'nullable|exists:users,id',
                'catatan_verifikasi' => 'nullable|string',
                'rating_toko' => 'nullable|numeric|min:0|max:5',
                'total_penjualan' => 'nullable|integer|min:0',
            ]);

            // Map API-friendly fields to database fields
            if (isset($validatedData['deskripsi'])) {
                $validatedData['deskripsi_toko'] = $validatedData['deskripsi'];
                unset($validatedData['deskripsi']);
            }

            $seller->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Penjual berhasil diperbarui',
                'data' => new SellerResource($seller)
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
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
    public function destroy($id)
    {
        try {
            $seller = Seller::find($id);

            if (!$seller) {
                return response()->json([
                    'success' => false,
                    'message' => 'Penjual tidak ditemukan'
                ], 404);
            }

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