<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Device;
use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Controller untuk manajemen profil pengguna
 * 
 * Controller ini menangani proses update dan delete profil pengguna
 */
class UserController extends Controller
{
    /**
     * Update data pengguna
     * 
     * @param UserUpdateRequest $request
     * @return JsonResponse
     */
    public function updateProfile(UserUpdateRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();

            // Update data pengguna
            $updateData = [
                'username' => $request->username ?? $user->username,
                'nama_depan' => $request->nama_depan ?? $user->nama_depan,
                'nama_belakang' => $request->nama_belakang ?? $user->nama_belakang,
                'nama_lengkap' => $request->nama_depan . ' ' . $request->nama_belakang,
                'jenis_kelamin' => $request->jenis_kelamin ?? $user->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir ?? $user->tanggal_lahir,
                'bio' => $request->bio ?? $user->bio,
                'pengaturan' => $request->pengaturan ?? $user->pengaturan,
                'url_media_sosial' => $request->url_media_sosial ?? $user->url_media_sosial,
                'bidang_interests' => $request->bidang_interests ?? $user->bidang_interests,
                'diperbarui_pada' => now(),
            ];

            if ($request->has('kata_sandi') && !empty($request->kata_sandi)) {
                $updateData['kata_sandi'] = Hash::make($request->kata_sandi);
            }

            $user->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'data' => new UserResource($user)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat update profil: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus akun pengguna
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAccount(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            // Validasi password untuk keamanan
            if ($request->has('password') && !Hash::check($request->password, $user->kata_sandi)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password tidak valid'
                ], 400);
            }

            // Hapus semua token Sanctum
            $user->tokens()->delete();

            // Hapus data terkait pengguna
            $user->sellers()->delete();
            $user->addresses()->delete();
            $user->verifications()->delete();
            $user->devices()->delete();
            $user->reviews()->delete();
            $user->activities()->delete();
            $user->searchHistories()->delete();
            $user->notifications()->delete();
            $user->carts()->delete();
            $user->orders()->delete();
            $user->cartItems()->delete();

            // Hapus pengguna
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Akun berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus akun: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Dapatkan data profil pengguna saat ini
     * 
     * @return JsonResponse
     */
    public function showProfile(): JsonResponse
    {
        try {
            $user = Auth::user();

            return response()->json([
                'success' => true,
                'data' => new UserResource($user)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data profil: ' . $e->getMessage()
            ], 500);
        }
    }
}