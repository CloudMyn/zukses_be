<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Http\Resources\ChatConversationResource;
use Illuminate\Http\Request;

class ChatConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $chatConversations = ChatConversation::paginate(15);
            return ChatConversationResource::collection($chatConversations);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data percakapan chat: ' . $e->getMessage()
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
                'tipe' => 'required|in:PRIVATE,GROUP,ORDER_SUPPORT,PRODUCT_SUPPORT,SYSTEM',
                'judul' => 'nullable|string|max:512',
                'owner_user_id' => 'nullable|exists:users,id',
                'owner_shop_profile_id' => 'nullable|exists:tb_penjual,id',
                'metadata' => 'nullable|json',
                'last_message_id' => 'nullable|exists:chat_pesan_chat,id',
                'last_message_at' => 'nullable|date',
                'is_open' => 'required|boolean',
                'dibuat_pada' => 'nullable|date',
                'diperbarui_pada' => 'nullable|date',
            ]);

            $chatConversation = ChatConversation::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Percakapan chat berhasil dibuat',
                'data' => new ChatConversationResource($chatConversation)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat percakapan chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ChatConversation $chatConversation)
    {
        try {
            return new ChatConversationResource($chatConversation);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data percakapan chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChatConversation $chatConversation)
    {
        try {
            $validatedData = $request->validate([
                'tipe' => 'sometimes|required|in:PRIVATE,GROUP,ORDER_SUPPORT,PRODUCT_SUPPORT,SYSTEM',
                'judul' => 'nullable|string|max:512',
                'owner_user_id' => 'nullable|exists:users,id',
                'owner_shop_profile_id' => 'nullable|exists:tb_penjual,id',
                'metadata' => 'nullable|json',
                'last_message_id' => 'nullable|exists:chat_pesan_chat,id',
                'last_message_at' => 'nullable|date',
                'is_open' => 'sometimes|required|boolean',
                'dibuat_pada' => 'nullable|date',
                'diperbarui_pada' => 'nullable|date',
            ]);

            $chatConversation->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Percakapan chat berhasil diperbarui',
                'data' => new ChatConversationResource($chatConversation)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui percakapan chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatConversation $chatConversation)
    {
        try {
            $chatConversation->delete();

            return response()->json([
                'success' => true,
                'message' => 'Percakapan chat berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus percakapan chat: ' . $e->getMessage()
            ], 500);
        }
    }
}