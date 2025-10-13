<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Http\Resources\AdminUserResource;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $adminUsers = AdminUser::paginate(15);
            return AdminUserResource::collection($adminUsers);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pengguna admin: ' . $e->getMessage()
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
                'role_admin' => 'required|in:SUPER_ADMIN,ADMIN_CONTENT,ADMIN_FINANCE,ADMIN_CUSTOMER,ADMIN_LOGISTIC',
                'permissions' => 'nullable|json',
                'is_active' => 'required|boolean',
                'last_login_at' => 'nullable|date',
                'dibuat_pada' => 'nullable|date',
                'diperbarui_pada' => 'nullable|date',
            ]);

            $adminUser = AdminUser::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Pengguna admin berhasil dibuat',
                'data' => new AdminUserResource($adminUser)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat pengguna admin: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AdminUser $adminUser)
    {
        try {
            return new AdminUserResource($adminUser);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pengguna admin: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdminUser $adminUser)
    {
        try {
            $validatedData = $request->validate([
                'id_user' => 'sometimes|required|exists:users,id',
                'role_admin' => 'sometimes|required|in:SUPER_ADMIN,ADMIN_CONTENT,ADMIN_FINANCE,ADMIN_CUSTOMER,ADMIN_LOGISTIC',
                'permissions' => 'nullable|json',
                'is_active' => 'sometimes|required|boolean',
                'last_login_at' => 'nullable|date',
                'dibuat_pada' => 'nullable|date',
                'diperbarui_pada' => 'nullable|date',
            ]);

            $adminUser->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Pengguna admin berhasil diperbarui',
                'data' => new AdminUserResource($adminUser)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui pengguna admin: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdminUser $adminUser)
    {
        try {
            $adminUser->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pengguna admin berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus pengguna admin: ' . $e->getMessage()
            ], 500);
        }
    }
}