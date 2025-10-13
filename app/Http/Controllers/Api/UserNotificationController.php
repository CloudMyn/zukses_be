<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use App\Http\Resources\UserNotificationResource;
use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $userNotifications = UserNotification::paginate(15);
            return UserNotificationResource::collection($userNotifications);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data notifikasi pengguna: ' . $e->getMessage()
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
                'tipe_notifikasi' => 'required|in:PESANAN,PEMBAYARAN,PENGIRIMAN,ULASAN,SYSTEM,CHAT',
                'judul_notifikasi' => 'required|string|max:255',
                'isi_notifikasi' => 'required|string',
                'data_notifikasi' => 'nullable|json',
                'url_redirect' => 'nullable|string|max:255',
                'gambar_notifikasi' => 'nullable|string|max:255',
                'is_dibaca' => 'required|boolean',
                'is_dikirim_push' => 'required|boolean',
                'is_dikirim_email' => 'required|boolean',
                'is_dikirim_sms' => 'required|boolean',
                'tanggal_dibaca' => 'nullable|date',
                'kadaluarsa_pada' => 'nullable|date',
                'dibuat_pada' => 'nullable|date',
            ]);

            $userNotification = UserNotification::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi pengguna berhasil dibuat',
                'data' => new UserNotificationResource($userNotification)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat notifikasi pengguna: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UserNotification $userNotification)
    {
        try {
            return new UserNotificationResource($userNotification);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data notifikasi pengguna: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserNotification $userNotification)
    {
        try {
            $validatedData = $request->validate([
                'id_user' => 'sometimes|required|exists:users,id',
                'tipe_notifikasi' => 'sometimes|required|in:PESANAN,PEMBAYARAN,PENGIRIMAN,ULASAN,SYSTEM,CHAT',
                'judul_notifikasi' => 'sometimes|required|string|max:255',
                'isi_notifikasi' => 'sometimes|required|string',
                'data_notifikasi' => 'nullable|json',
                'url_redirect' => 'nullable|string|max:255',
                'gambar_notifikasi' => 'nullable|string|max:255',
                'is_dibaca' => 'sometimes|required|boolean',
                'is_dikirim_push' => 'sometimes|required|boolean',
                'is_dikirim_email' => 'sometimes|required|boolean',
                'is_dikirim_sms' => 'sometimes|required|boolean',
                'tanggal_dibaca' => 'nullable|date',
                'kadaluarsa_pada' => 'nullable|date',
                'dibuat_pada' => 'nullable|date',
            ]);

            $userNotification->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi pengguna berhasil diperbarui',
                'data' => new UserNotificationResource($userNotification)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui notifikasi pengguna: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserNotification $userNotification)
    {
        try {
            $userNotification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi pengguna berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus notifikasi pengguna: ' . $e->getMessage()
            ], 500);
        }
    }
}