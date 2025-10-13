<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserActivity;
use App\Http\Resources\UserActivityResource;
use Illuminate\Http\Request;

class UserActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $userActivities = UserActivity::paginate(15);
            return UserActivityResource::collection($userActivities);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data aktivitas pengguna: ' . $e->getMessage()
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
                'sesi_id' => 'nullable|string|max:255',
                'tipe_aktivitas' => 'required|in:LOGIN,LOGOUT,VIEW_PRODUK,CARI_PRODUK,TAMBAH_KERANJANG,CHECKOUT,PAYMENT,REVIEW,CHAT',
                'data_aktivitas' => 'nullable|json',
                'ip_address' => 'nullable|string|max:45',
                'user_agent' => 'nullable|string|max:500',
                'referrer' => 'nullable|string|max:255',
                'halaman_asal' => 'nullable|string|max:255',
                'dibuat_pada' => 'nullable|date',
            ]);

            $userActivity = UserActivity::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Aktivitas pengguna berhasil dibuat',
                'data' => new UserActivityResource($userActivity)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat aktivitas pengguna: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UserActivity $userActivity)
    {
        try {
            return new UserActivityResource($userActivity);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data aktivitas pengguna: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserActivity $userActivity)
    {
        try {
            $validatedData = $request->validate([
                'id_user' => 'sometimes|required|exists:users,id',
                'sesi_id' => 'nullable|string|max:255',
                'tipe_aktivitas' => 'sometimes|required|in:LOGIN,LOGOUT,VIEW_PRODUK,CARI_PRODUK,TAMBAH_KERANJANG,CHECKOUT,PAYMENT,REVIEW,CHAT',
                'data_aktivitas' => 'nullable|json',
                'ip_address' => 'nullable|string|max:45',
                'user_agent' => 'nullable|string|max:500',
                'referrer' => 'nullable|string|max:255',
                'halaman_asal' => 'nullable|string|max:255',
                'dibuat_pada' => 'nullable|date',
            ]);

            $userActivity->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Aktivitas pengguna berhasil diperbarui',
                'data' => new UserActivityResource($userActivity)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui aktivitas pengguna: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserActivity $userActivity)
    {
        try {
            $userActivity->delete();

            return response()->json([
                'success' => true,
                'message' => 'Aktivitas pengguna berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus aktivitas pengguna: ' . $e->getMessage()
            ], 500);
        }
    }
}