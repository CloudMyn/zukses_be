<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Controller untuk manajemen pengguna
 * 
 * Controller ini menangani operasi CRUD untuk pengguna
 */
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Check if user is admin
            if (Auth::user()->tipe_user !== 'ADMIN') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke resource ini'
                ], 403);
            }

            $perPage = request('per_page', 15);
            $search = request('search');
            $status = request('status');

            $query = User::query();

            // Apply search filter
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('nama_lengkap', 'like', "%{$search}%");
                });
            }

            // Apply status filter
            if ($status) {
                $query->where('status', $status);
            }

            $users = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data pengguna berhasil diambil',
                'data' => UserResource::collection($users),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pengguna: ' . $e->getMessage()
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
                'username' => 'required|string|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'nomor_telepon' => 'nullable|string|unique:users,nomor_telepon',
                'kata_sandi' => 'required|string|min:8',
                'tipe_user' => 'required|in:ADMIN,PELANGGAN,PEDAGANG',
                'status' => 'required|in:AKTIF,TIDAK_AKTIF,DIBLOKIR,SUSPEND',
                'nama_depan' => 'nullable|string|max:255',
                'nama_belakang' => 'nullable|string|max:255',
                'jenis_kelamin' => 'nullable|in:LAKI_LAKI,PEREMPUAN,RAHASIA',
                'tanggal_lahir' => 'nullable|date',
                'bio' => 'nullable|string|max:500',
            ]);

            $validatedData['kata_sandi'] = Hash::make($validatedData['kata_sandi']);

            $user = User::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil dibuat',
                'data' => new UserResource($user)
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
                'message' => 'Terjadi kesalahan saat membuat pengguna: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data pengguna berhasil diambil',
                'data' => new UserResource($user)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pengguna: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan'
                ], 404);
            }

            $validatedData = $request->validate([
                'username' => 'sometimes|required|string|unique:users,username,' . $user->id,
                'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
                'nomor_telepon' => 'nullable|string|unique:users,nomor_telepon,' . $user->id,
                'kata_sandi' => 'sometimes|required|string|min:8',
                'tipe_user' => 'sometimes|required|in:ADMIN,PELANGGAN,PEDAGANG',
                'status' => 'sometimes|required|in:AKTIF,TIDAK_AKTIF,DIBLOKIR,SUSPEND',
                'nama_depan' => 'nullable|string|max:255',
                'nama_belakang' => 'nullable|string|max:255',
                'jenis_kelamin' => 'nullable|in:LAKI_LAKI,PEREMPUAN,RAHASIA',
                'tanggal_lahir' => 'nullable|date',
                'bio' => 'nullable|string|max:500',
            ]);

            if (isset($validatedData['kata_sandi'])) {
                $validatedData['kata_sandi'] = Hash::make($validatedData['kata_sandi']);
            }

            $user->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil diperbarui',
                'data' => new UserResource($user)
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
                'message' => 'Terjadi kesalahan saat memperbarui pengguna: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan'
                ], 404);
            }

            // Hapus semua token Sanctum
            $user->tokens()->delete();

            // Hapus data terkait pengguna yang ada (gunakan safe delete)
            try {
                $user->verifications()->delete();
                $user->devices()->delete();
            } catch (\Exception $e) {
                // Ignore relationship errors for non-existent relationships
            }

            // Hapus pengguna
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus pengguna: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update data pengguna (profil)
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