<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Verification;
use App\Http\Resources\VerificationResource;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $verifications = Verification::paginate(15);
            return VerificationResource::collection($verifications);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data verifikasi: ' . $e->getMessage()
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
                'id_user' => 'nullable|exists:users,id',
                'jenis_verifikasi' => 'required|in:EMAIL,TELEPON,KTP,NPWP',
                'nilai_verifikasi' => 'required|string',
                'kode_verifikasi' => 'required|string',
                'kedaluwarsa_pada' => 'required|date',
                'telah_digunakan' => 'required|boolean',
                'jumlah_coba' => 'required|integer|min:0',
            ]);

            $verification = Verification::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Verifikasi berhasil dibuat',
                'data' => new VerificationResource($verification)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat verifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Verification $verification)
    {
        try {
            return new VerificationResource($verification);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data verifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Verification $verification)
    {
        try {
            $validatedData = $request->validate([
                'id_user' => 'sometimes|nullable|exists:users,id',
                'jenis_verifikasi' => 'sometimes|required|in:EMAIL,TELEPON,KTP,NPWP',
                'nilai_verifikasi' => 'sometimes|required|string',
                'kode_verifikasi' => 'sometimes|required|string',
                'kedaluwarsa_pada' => 'sometimes|required|date',
                'telah_digunakan' => 'sometimes|required|boolean',
                'jumlah_coba' => 'sometimes|required|integer|min:0',
            ]);

            $verification->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Verifikasi berhasil diperbarui',
                'data' => new VerificationResource($verification)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui verifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Verification $verification)
    {
        try {
            $verification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Verifikasi berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus verifikasi: ' . $e->getMessage()
            ], 500);
        }
    }
}