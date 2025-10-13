<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Http\Resources\CityResource;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $cities = City::paginate(15);
            return CityResource::collection($cities);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data kota: ' . $e->getMessage()
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
                'provinsi_id' => 'required|exists:master_provinsi,id',
                'nama' => 'required|string',
            ]);

            $city = City::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Kota berhasil dibuat',
                'data' => new CityResource($city)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat kota: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        try {
            return new CityResource($city);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data kota: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $city)
    {
        try {
            $validatedData = $request->validate([
                'provinsi_id' => 'sometimes|required|exists:master_provinsi,id',
                'nama' => 'sometimes|required|string',
            ]);

            $city->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Kota berhasil diperbarui',
                'data' => new CityResource($city)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui kota: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        try {
            $city->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kota berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus kota: ' . $e->getMessage()
            ], 500);
        }
    }
}