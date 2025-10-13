<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PostalCode;
use App\Http\Resources\PostalCodeResource;
use Illuminate\Http\Request;

class PostalCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $postalCodes = PostalCode::paginate(15);
            return PostalCodeResource::collection($postalCodes);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data kode pos: ' . $e->getMessage()
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
                'kecamatan_id' => 'required|exists:master_kecamatan,id',
                'kode' => 'required|string|unique:master_kode_pos,kode',
            ]);

            $postalCode = PostalCode::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Kode pos berhasil dibuat',
                'data' => new PostalCodeResource($postalCode)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat kode pos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PostalCode $postalCode)
    {
        try {
            return new PostalCodeResource($postalCode);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data kode pos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PostalCode $postalCode)
    {
        try {
            $validatedData = $request->validate([
                'kecamatan_id' => 'sometimes|required|exists:master_kecamatan,id',
                'kode' => 'sometimes|required|string|unique:master_kode_pos,kode,' . $postalCode->id,
            ]);

            $postalCode->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Kode pos berhasil diperbarui',
                'data' => new PostalCodeResource($postalCode)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui kode pos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostalCode $postalCode)
    {
        try {
            $postalCode->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kode pos berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus kode pos: ' . $e->getMessage()
            ], 500);
        }
    }
}