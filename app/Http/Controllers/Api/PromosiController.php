<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promosi;
use App\Models\ProdukPromosi;
use App\Models\RiwayatPenggunaanPromosi;
use App\Http\Resources\PromosiResource;
use App\Http\Resources\PromosiCollection;
use App\Http\Requests\CreatePromosiRequest;
use App\Http\Requests\UpdatePromosiRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class PromosiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Promosi::query();

            // If the user is not an admin, only show active promotions
            if (Auth::user()->tipe_user !== 'ADMIN') {
                $query->where('status_aktif', true);
            }

            // Apply filters
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('kode_promosi', 'LIKE', "%{$search}%")
                      ->orWhere('nama_promosi', 'LIKE', "%{$search}%")
                      ->orWhere('deskripsi', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('jenis_promosi')) {
                $query->where('jenis_promosi', $request->get('jenis_promosi'));
            }

            if ($request->has('status_aktif')) {
                $status = filter_var($request->get('status_aktif'), FILTER_VALIDATE_BOOLEAN);
                $query->where('status_aktif', $status);
            }

            // Apply date range filter if provided
            if ($request->has('tanggal_mulai') && $request->has('tanggal_berakhir')) {
                $query->whereBetween('tanggal_mulai', [
                    $request->get('tanggal_mulai'), 
                    $request->get('tanggal_berakhir')
                ]);
            }

            $perPage = $request->get('per_page', 15);
            $promosi = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Daftar promosi berhasil diambil',
                'data' => new PromosiCollection($promosi),
                'pagination' => [
                    'current_page' => $promosi->currentPage(),
                    'last_page' => $promosi->lastPage(),
                    'per_page' => $promosi->perPage(),
                    'total' => $promosi->total(),
                    'from' => $promosi->firstItem(),
                    'to' => $promosi->lastItem()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil daftar promosi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePromosiRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            
            // Set the creator to the current user
            $validatedData['id_pembuat'] = Auth::id();
            $validatedData['id_pembaharu_terakhir'] = Auth::id();
            
            $promosi = Promosi::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Promosi berhasil dibuat',
                'data' => new PromosiResource($promosi)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat promosi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $promosi = Promosi::find($id)->load(['pembuat', 'kategori_produk', 'produk', 'riwayat_penggunaan']);

            // Check user role - non-admins can only see active promotions
            if (Auth::user()->tipe_user !== 'ADMIN' && !$promosi->status_aktif) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Promosi tidak aktif.'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail promosi berhasil diambil',
                'data' => new PromosiResource($promosi)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil detail promosi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePromosiRequest $request, $id): JsonResponse
    {
        try {
            $promosi = Promosi::find($id);
            $validatedData = $request->validated();
            
            // Update the last modified by field
            $validatedData['id_pembaharu_terakhir'] = Auth::id();
            
            $promosi->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Promosi berhasil diperbarui',
                'data' => new PromosiResource($promosi)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui promosi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $promosi = Promosi::find($id);
            // Check user role - only admin can delete promotions
            if (Auth::user()->tipe_user !== 'ADMIN') {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Hanya admin yang dapat menghapus promosi.'
                ], 403);
            }

            $promosi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Promosi berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus promosi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate promotion code
     */
    public function validatePromo(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'kode_promosi' => 'required|string|exists:tb_promosi,kode_promosi',
                'total_pembelian' => 'required|numeric|min:0',
                'id_produk' => 'nullable|exists:tb_produk,id',  // For product-specific promotions
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $kodePromosi = $request->kode_promosi;
            $totalPembelian = $request->total_pembelian;
            $idProduk = $request->id_produk;

            $promosi = Promosi::where('kode_promosi', $kodePromosi)->first();

            if (!$promosi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode promosi tidak valid'
                ], 404);
            }

            // Check if promotion is active
            if (!$promosi->isActive()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Promosi tidak aktif atau sudah kadaluarsa'
                ], 400);
            }

            // Check if user has exceeded maximum usage per user
            if ($promosi->jumlah_maksimum_penggunaan_per_pengguna > 0 && Auth::check()) {
                $userUsageId = RiwayatPenggunaanPromosi::where('id_promosi', $promosi->id)
                    ->where('id_pengguna', Auth::id())
                    ->count();
                    
                if ($userUsageId >= $promosi->jumlah_maksimum_penggunaan_per_pengguna) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda telah melebihi batas penggunaan promosi ini'
                    ], 400);
                }
            }

            // Check minimum purchase requirement
            if ($totalPembelian < $promosi->minimum_pembelian) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total pembelian tidak memenuhi syarat minimum untuk promosi ini',
                    'minimum_pembelian' => $promosi->minimum_pembelian
                ], 400);
            }

            // Check if promotion is applicable to specific product (if needed)
            if ($idProduk && $promosi->jenis_promosi === 'KELOMPOK_PRODUK') {
                $isProductEligible = ProdukPromosi::where('id_promosi', $promosi->id)
                    ->where('id_produk', $idProduk)
                    ->exists();
                    
                if (!$isProductEligible) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Promosi ini tidak berlaku untuk produk yang dipilih'
                    ], 400);
                }
            }

            // All validations passed, return promotion details and calculated discount
            $diskon = $promosi->hitungDiskon($totalPembelian);
            
            // Ensure discount doesn't exceed total purchase
            $diskon = min($diskon, $totalPembelian);

            return response()->json([
                'success' => true,
                'message' => 'Kode promosi valid',
                'data' => [
                    'promosi' => new PromosiResource($promosi),
                    'diskon_diterapkan' => $diskon,
                    'total_pembayaran_setelah_diskon' => $totalPembelian - $diskon
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat validasi promosi: ' . $e->getMessage()
            ], 500);
        }
    }
}