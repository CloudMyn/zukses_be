<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MessageReaction;
use App\Http\Resources\MessageReactionResource;
use Illuminate\Http\Request;

class MessageReactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $messageReactions = MessageReaction::paginate(15);
            return MessageReactionResource::collection($messageReactions);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data reaksi pesan: ' . $e->getMessage()
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
                'pesan_id' => 'required|exists:chat_pesan_chat,id',
                'user_id' => 'required|exists:users,id',
                'reaksi' => 'required|string|max:10',
                'pesan_balasan' => 'nullable|string|max:255',
                'dibuat_pada' => 'nullable|date',
            ]);

            $messageReaction = MessageReaction::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Reaksi pesan berhasil dibuat',
                'data' => new MessageReactionResource($messageReaction)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat reaksi pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MessageReaction $messageReaction)
    {
        try {
            return new MessageReactionResource($messageReaction);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data reaksi pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MessageReaction $messageReaction)
    {
        try {
            $validatedData = $request->validate([
                'pesan_id' => 'sometimes|required|exists:chat_pesan_chat,id',
                'user_id' => 'sometimes|required|exists:users,id',
                'reaksi' => 'sometimes|required|string|max:10',
                'pesan_balasan' => 'nullable|string|max:255',
                'dibuat_pada' => 'nullable|date',
            ]);

            $messageReaction->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Reaksi pesan berhasil diperbarui',
                'data' => new MessageReactionResource($messageReaction)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui reaksi pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MessageReaction $messageReaction)
    {
        try {
            $messageReaction->delete();

            return response()->json([
                'success' => true,
                'message' => 'Reaksi pesan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus reaksi pesan: ' . $e->getMessage()
            ], 500);
        }
    }
}