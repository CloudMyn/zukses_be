<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Http\Resources\DeviceResource;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $devices = Device::paginate(15);
            return DeviceResource::collection($devices);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data perangkat: ' . $e->getMessage()
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
                'device_id' => 'required|string|unique:perangkat_pengguna,device_id',
                'device_type' => 'required|in:MOBILE,TABLET,DESKTOP,TV',
                'device_name' => 'required|string',
                'operating_system' => 'required|string',
                'app_version' => 'nullable|string',
                'push_token' => 'nullable|string',
                'adalah_device_terpercaya' => 'required|boolean',
                'terakhir_aktif_pada' => 'required|date',
            ]);

            $device = Device::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Perangkat berhasil dibuat',
                'data' => new DeviceResource($device)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat perangkat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device)
    {
        try {
            return new DeviceResource($device);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data perangkat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Device $device)
    {
        try {
            $validatedData = $request->validate([
                'id_user' => 'sometimes|required|exists:users,id',
                'device_id' => 'sometimes|required|string|unique:perangkat_pengguna,device_id,' . $device->id,
                'device_type' => 'sometimes|required|in:MOBILE,TABLET,DESKTOP,TV',
                'device_name' => 'sometimes|required|string',
                'operating_system' => 'sometimes|required|string',
                'app_version' => 'nullable|string',
                'push_token' => 'nullable|string',
                'adalah_device_terpercaya' => 'sometimes|required|boolean',
                'terakhir_aktif_pada' => 'sometimes|required|date',
            ]);

            $device->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Perangkat berhasil diperbarui',
                'data' => new DeviceResource($device)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui perangkat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        try {
            $device->delete();

            return response()->json([
                'success' => true,
                'message' => 'Perangkat berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus perangkat: ' . $e->getMessage()
            ], 500);
        }
    }
}