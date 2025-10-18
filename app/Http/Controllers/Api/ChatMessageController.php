<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Http\Resources\ChatMessageResource;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $chatMessages = ChatMessage::paginate(15);
            return ChatMessageResource::collection($chatMessages);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pesan chat: ' . $e->getMessage()
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
                'pengirim_user_id' => 'nullable|exists:users,id',
                'pengirim_shop_profile_id' => 'nullable|exists:tb_penjual,id',
                'konten' => 'required|string|max:1000',
                'tipe_konten' => 'required|in:TEXT,IMAGE,VIDEO,AUDIO,DOCUMENT,LOCATION,CONTACT,SYSTEM',
                'metadata' => 'nullable|json',
                'parent_message_id' => 'nullable|exists:chat_pesan_chat,id',
                'reply_to_message_id' => 'nullable|exists:chat_pesan_chat,id',
                'diedit_pada' => 'nullable|date',
                'is_dihapus' => 'nullable|boolean',
                'dihapus_pada' => 'nullable|date',
            ]);

            $chatMessage = ChatMessage::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Pesan chat berhasil dibuat',
                'data' => new ChatMessageResource($chatMessage)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat pesan chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ChatMessage $chatMessage)
    {
        try {
            return new ChatMessageResource($chatMessage);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pesan chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChatMessage $chatMessage)
    {
        try {
            $validatedData = $request->validate([
                'percakapan_id' => 'sometimes|required|exists:chat_percakapan,id',
                'pengirim_user_id' => 'nullable|exists:users,id',
                'pengirim_shop_profile_id' => 'nullable|exists:tb_penjual,id',
                'konten' => 'sometimes|required|string|max:1000',
                'tipe_konten' => 'sometimes|required|in:TEXT,IMAGE,VIDEO,AUDIO,DOCUMENT,LOCATION,CONTACT,SYSTEM',
                'metadata' => 'nullable|json',
                'parent_message_id' => 'nullable|exists:chat_pesan_chat,id',
                'reply_to_message_id' => 'nullable|exists:chat_pesan_chat,id',
                'diedit_pada' => 'nullable|date',
                'is_dihapus' => 'nullable|boolean',
                'dihapus_pada' => 'nullable|date',
            ]);

            $chatMessage->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Pesan chat berhasil diperbarui',
                'data' => new ChatMessageResource($chatMessage)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui pesan chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatMessage $chatMessage)
    {
        try {
            $chatMessage->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pesan chat berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus pesan chat: ' . $e->getMessage()
            ], 500);
        }
    }
}