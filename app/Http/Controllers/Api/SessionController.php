<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Http\Resources\SessionResource;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $sessions = Session::paginate(15);
            return SessionResource::collection($sessions);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data sesi: ' . $e->getMessage()
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
                'id' => 'required|string|unique:sesi_pengguna,id',
                'id_user' => 'nullable|exists:users,id',
                'ip_address' => 'nullable|string|max:45',
                'user_agent' => 'nullable|string',
                'payload' => 'required|string',
                'aktivitas_terakhir' => 'required|integer',
            ]);

            $session = Session::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Sesi berhasil dibuat',
                'data' => new SessionResource($session)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat sesi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Session $session)
    {
        try {
            return new SessionResource($session);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data sesi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Session $session)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'sometimes|required|string|unique:sesi_pengguna,id,' . $session->id,
                'id_user' => 'sometimes|nullable|exists:users,id',
                'ip_address' => 'nullable|string|max:45',
                'user_agent' => 'nullable|string',
                'payload' => 'sometimes|required|string',
                'aktivitas_terakhir' => 'sometimes|required|integer',
            ]);

            $session->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Sesi berhasil diperbarui',
                'data' => new SessionResource($session)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui sesi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Session $session)
    {
        try {
            $session->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sesi berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus sesi: ' . $e->getMessage()
            ], 500);
        }
    }
}