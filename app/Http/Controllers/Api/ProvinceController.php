<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Http\Resources\ProvinceResource;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $provinces = Province::paginate(15);
            return ProvinceResource::collection($provinces);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data provinsi: ' . $e->getMessage()
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
                'nama' => 'required|string|unique:master_provinsi,nama',
            ]);

            $province = Province::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Provinsi berhasil dibuat',
                'data' => new ProvinceResource($province)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat provinsi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Province $province)
    {
        try {
            return new ProvinceResource($province);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data provinsi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Province $province)
    {
        try {
            $validatedData = $request->validate([
                'nama' => 'sometimes|required|string|unique:master_provinsi,nama,' . $province->id,
            ]);

            $province->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Provinsi berhasil diperbarui',
                'data' => new ProvinceResource($province)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui provinsi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Province $province)
    {
        try {
            $province->delete();

            return response()->json([
                'success' => true,
                'message' => 'Provinsi berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus provinsi: ' . $e->getMessage()
            ], 500);
        }
    }
}