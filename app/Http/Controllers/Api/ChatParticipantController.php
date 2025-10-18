<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatParticipant;
use App\Http\Resources\ChatParticipantResource;
use Illuminate\Http\Request;

class ChatParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $chatParticipants = ChatParticipant::paginate(15);
            return ChatParticipantResource::collection($chatParticipants);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data peserta chat: ' . $e->getMessage()
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
                'percakapan_id' => 'required|exists:chat_percakapan,id',
                'user_id' => 'nullable|exists:users,id',
                'shop_profile_id' => 'nullable|exists:tb_penjual,id',
                'role' => 'required|in:PARTICIPANT,ADMIN,AGENT,OWNER',
                'bergabung_pada' => 'nullable|date',
                'keluar_pada' => 'nullable|date',
                'last_read_message_id' => 'nullable|exists:chat_pesan_chat,id',
                'terakhir_dibaca_pada' => 'nullable|date',
                'jumlah_belum_dibaca' => 'required|integer|min:0',
                'dihentikan_hingga' => 'nullable|date',
                'is_blocked' => 'required|boolean',
                'preferences' => 'nullable|json',
                'dibuat_pada' => 'nullable|date',
                'diperbarui_pada' => 'nullable|date',
            ]);

            $chatParticipant = ChatParticipant::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Peserta chat berhasil dibuat',
                'data' => new ChatParticipantResource($chatParticipant)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat peserta chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ChatParticipant $chatParticipant)
    {
        try {
            return new ChatParticipantResource($chatParticipant);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data peserta chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChatParticipant $chatParticipant)
    {
        try {
            $validatedData = $request->validate([
                'percakapan_id' => 'sometimes|required|exists:chat_percakapan,id',
                'user_id' => 'nullable|exists:users,id',
                'shop_profile_id' => 'nullable|exists:tb_penjual,id',
                'role' => 'sometimes|required|in:PARTICIPANT,ADMIN,AGENT,OWNER',
                'bergabung_pada' => 'nullable|date',
                'keluar_pada' => 'nullable|date',
                'last_read_message_id' => 'nullable|exists:chat_pesan_chat,id',
                'terakhir_dibaca_pada' => 'nullable|date',
                'jumlah_belum_dibaca' => 'sometimes|required|integer|min:0',
                'dihentikan_hingga' => 'nullable|date',
                'is_blocked' => 'sometimes|required|boolean',
                'preferences' => 'nullable|json',
                'dibuat_pada' => 'nullable|date',
                'diperbarui_pada' => 'nullable|date',
            ]);

            $chatParticipant->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Peserta chat berhasil diperbarui',
                'data' => new ChatParticipantResource($chatParticipant)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui peserta chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatParticipant $chatParticipant)
    {
        try {
            $chatParticipant->delete();

            return response()->json([
                'success' => true,
                'message' => 'Peserta chat berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus peserta chat: ' . $e->getMessage()
            ], 500);
        }
    }
}