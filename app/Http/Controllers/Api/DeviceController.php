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
            $devices = Device::where('id_user', auth()->id())->paginate(15);
            return response()->json([
                'success' => true,
                'message' => 'Data perangkat berhasil diambil',
                'data' => DeviceResource::collection($devices),
                'pagination' => [
                    'current_page' => $devices->currentPage(),
                    'last_page' => $devices->lastPage(),
                    'per_page' => $devices->perPage(),
                    'total' => $devices->total()
                ]
            ]);
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
                'id_user' => 'sometimes|required|exists:users,id',
                'device_id' => 'required|string|unique:devices,device_id',
                'device_type' => 'required|in:mobile,tablet,desktop,tv,MOBILE,TABLET,DESKTOP,TV',
                'device_name' => 'required|string',
                'operating_system' => 'sometimes|required|string',
                'app_version' => 'nullable|string',
                'push_token' => 'nullable|string',
                'is_trusted' => 'sometimes|boolean',
                'last_used_at' => 'sometimes|date',
            ]);

            // Map test-friendly field names to actual database column names
            if (isset($validatedData['is_trusted'])) {
                $validatedData['adalah_device_terpercaya'] = $validatedData['is_trusted'];
                unset($validatedData['is_trusted']);
            }

            if (isset($validatedData['last_used_at'])) {
                $validatedData['terakhir_aktif_pada'] = $validatedData['last_used_at'];
                unset($validatedData['last_used_at']);
            }

            // Set default values if not provided
            $validatedData['id_user'] = $validatedData['id_user'] ?? auth()->id();
            $validatedData['operating_system'] = $validatedData['operating_system'] ?? 'Unknown';
            $validatedData['adalah_device_terpercaya'] = $validatedData['adalah_device_terpercaya'] ?? false;
            $validatedData['terakhir_aktif_pada'] = $validatedData['terakhir_aktif_pada'] ?? now();
            $validatedData['dibuat_pada'] = now();
            $validatedData['diperbarui_pada'] = now();

            // Convert device_type to uppercase to match database enum
            if (isset($validatedData['device_type'])) {
                $validatedData['device_type'] = strtoupper($validatedData['device_type']);
            }

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
            return response()->json([
                'success' => true,
                'message' => 'Data perangkat berhasil diambil',
                'data' => new DeviceResource($device)
            ]);
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
                'device_id' => 'sometimes|required|string|unique:devices,device_id,' . $device->id,
                'device_type' => 'sometimes|required|in:mobile,tablet,desktop,tv,MOBILE,TABLET,DESKTOP,TV',
                'device_name' => 'sometimes|required|string',
                'operating_system' => 'sometimes|required|string',
                'app_version' => 'nullable|string',
                'push_token' => 'nullable|string',
                'is_trusted' => 'sometimes|boolean',
                'last_used_at' => 'sometimes|date',
            ]);

            // Map test-friendly field names to actual database column names
            if (isset($validatedData['is_trusted'])) {
                $validatedData['adalah_device_terpercaya'] = $validatedData['is_trusted'];
                unset($validatedData['is_trusted']);
            }

            if (isset($validatedData['last_used_at'])) {
                $validatedData['terakhir_aktif_pada'] = $validatedData['last_used_at'];
                unset($validatedData['last_used_at']);
            }

            $validatedData['diperbarui_pada'] = now();

            // Convert device_type to uppercase to match database enum
            if (isset($validatedData['device_type'])) {
                $validatedData['device_type'] = strtoupper($validatedData['device_type']);
            }

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

    /**
     * Mark device as trusted/untrusted.
     */
    public function trust(Request $request, Device $device)
    {
        try {
            $validatedData = $request->validate([
                'is_trusted' => 'required|boolean',
            ]);

            $device->update([
                'adalah_device_terpercaya' => $validatedData['is_trusted'],
                'diperbarui_pada' => now(),
            ]);

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
}