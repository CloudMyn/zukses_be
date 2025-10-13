<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Http\Resources\DistrictResource;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $districts = District::paginate(15);
            return DistrictResource::collection($districts);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data kecamatan: ' . $e->getMessage()
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
                'kota_id' => 'required|exists:master_kota,id',
                'nama' => 'required|string',
            ]);

            $district = District::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Kecamatan berhasil dibuat',
                'data' => new DistrictResource($district)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat kecamatan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(District $district)
    {
        try {
            return new DistrictResource($district);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data kecamatan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, District $district)
    {
        try {
            $validatedData = $request->validate([
                'kota_id' => 'sometimes|required|exists:master_kota,id',
                'nama' => 'sometimes|required|string',
            ]);

            $district->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Kecamatan berhasil diperbarui',
                'data' => new DistrictResource($district)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui kecamatan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(District $district)
    {
        try {
            $district->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kecamatan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus kecamatan: ' . $e->getMessage()
            ], 500);
        }
    }
}