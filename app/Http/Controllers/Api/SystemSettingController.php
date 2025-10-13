<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Http\Resources\SystemSettingResource;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $systemSettings = SystemSetting::paginate(15);
            return SystemSettingResource::collection($systemSettings);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pengaturan sistem: ' . $e->getMessage()
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
                'kunci_pengaturan' => 'required|string|unique:pengaturan_sistem,kunci_pengaturan|max:255',
                'nilai_pengaturan' => 'required|string',
                'tipe_pengaturan' => 'required|in:STRING,NUMBER,BOOLEAN,JSON',
                'grup_pengaturan' => 'nullable|string|max:255',
                'deskripsi_pengaturan' => 'nullable|string|max:500',
                'is_public' => 'nullable|boolean',
            ]);

            $systemSetting = SystemSetting::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Pengaturan sistem berhasil dibuat',
                'data' => new SystemSettingResource($systemSetting)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat pengaturan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SystemSetting $systemSetting)
    {
        try {
            return new SystemSettingResource($systemSetting);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pengaturan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SystemSetting $systemSetting)
    {
        try {
            $validatedData = $request->validate([
                'kunci_pengaturan' => 'sometimes|required|string|unique:pengaturan_sistem,kunci_pengaturan,' . $systemSetting->id . '|max:255',
                'nilai_pengaturan' => 'sometimes|required|string',
                'tipe_pengaturan' => 'sometimes|required|in:STRING,NUMBER,BOOLEAN,JSON',
                'grup_pengaturan' => 'nullable|string|max:255',
                'deskripsi_pengaturan' => 'nullable|string|max:500',
                'is_public' => 'nullable|boolean',
            ]);

            $systemSetting->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Pengaturan sistem berhasil diperbarui',
                'data' => new SystemSettingResource($systemSetting)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui pengaturan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SystemSetting $systemSetting)
    {
        try {
            $systemSetting->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pengaturan sistem berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus pengaturan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}