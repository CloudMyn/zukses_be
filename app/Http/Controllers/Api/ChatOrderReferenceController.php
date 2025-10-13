<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatOrderReference;
use App\Http\Resources\ChatOrderReferenceResource;
use Illuminate\Http\Request;

class ChatOrderReferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $chatOrderReferences = ChatOrderReference::paginate(15);
            return ChatOrderReferenceResource::collection($chatOrderReferences);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data referensi order chat: ' . $e->getMessage()
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
                'order_id' => 'required|exists:orders,id',
                'posisi_mulai' => 'nullable|integer',
                'posisi_akhir' => 'nullable|integer',
                'konten_asli' => 'nullable|string|max:500',
            ]);

            $chatOrderReference = ChatOrderReference::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Referensi order chat berhasil dibuat',
                'data' => new ChatOrderReferenceResource($chatOrderReference)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat referensi order chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ChatOrderReference $chatOrderReference)
    {
        try {
            return new ChatOrderReferenceResource($chatOrderReference);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data referensi order chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChatOrderReference $chatOrderReference)
    {
        try {
            $validatedData = $request->validate([
                'pesan_id' => 'sometimes|required|exists:chat_pesan_chat,id',
                'order_id' => 'sometimes|required|exists:orders,id',
                'posisi_mulai' => 'nullable|integer',
                'posisi_akhir' => 'nullable|integer',
                'konten_asli' => 'nullable|string|max:500',
            ]);

            $chatOrderReference->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Referensi order chat berhasil diperbarui',
                'data' => new ChatOrderReferenceResource($chatOrderReference)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui referensi order chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatOrderReference $chatOrderReference)
    {
        try {
            $chatOrderReference->delete();

            return response()->json([
                'success' => true,
                'message' => 'Referensi order chat berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus referensi order chat: ' . $e->getMessage()
            ], 500);
        }
    }
}