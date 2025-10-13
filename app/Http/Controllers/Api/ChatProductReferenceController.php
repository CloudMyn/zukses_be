<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatProductReference;
use App\Http\Resources\ChatProductReferenceResource;
use Illuminate\Http\Request;

class ChatProductReferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $chatProductReferences = ChatProductReference::paginate(15);
            return ChatProductReferenceResource::collection($chatProductReferences);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data referensi produk chat: ' . $e->getMessage()
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
                'produk_id' => 'required|exists:produk,id',
                'posisi_mulai' => 'nullable|integer',
                'posisi_akhir' => 'nullable|integer',
                'konten_asli' => 'nullable|string|max:500',
            ]);

            $chatProductReference = ChatProductReference::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Referensi produk chat berhasil dibuat',
                'data' => new ChatProductReferenceResource($chatProductReference)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat referensi produk chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ChatProductReference $chatProductReference)
    {
        try {
            return new ChatProductReferenceResource($chatProductReference);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data referensi produk chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChatProductReference $chatProductReference)
    {
        try {
            $validatedData = $request->validate([
                'pesan_id' => 'sometimes|required|exists:chat_pesan_chat,id',
                'produk_id' => 'sometimes|required|exists:produk,id',
                'posisi_mulai' => 'nullable|integer',
                'posisi_akhir' => 'nullable|integer',
                'konten_asli' => 'nullable|string|max:500',
            ]);

            $chatProductReference->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Referensi produk chat berhasil diperbarui',
                'data' => new ChatProductReferenceResource($chatProductReference)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui referensi produk chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatProductReference $chatProductReference)
    {
        try {
            $chatProductReference->delete();

            return response()->json([
                'success' => true,
                'message' => 'Referensi produk chat berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus referensi produk chat: ' . $e->getMessage()
            ], 500);
        }
    }
}