<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MessageAttachment;
use App\Http\Resources\MessageAttachmentResource;
use Illuminate\Http\Request;

class MessageAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $messageAttachments = MessageAttachment::paginate(15);
            return MessageAttachmentResource::collection($messageAttachments);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data lampiran pesan: ' . $e->getMessage()
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
                'tipe_lampiran' => 'required|in:IMAGE,VIDEO,AUDIO,DOCUMENT,FILE',
                'url_lampiran' => 'required|url|max:2048',
                'nama_file' => 'required|string|max:255',
                'ukuran_file' => 'nullable|integer',
                'tipe_file' => 'nullable|string|max:100',
                'deskripsi' => 'nullable|string|max:500',
                'is_inline' => 'nullable|boolean',
            ]);

            $messageAttachment = MessageAttachment::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Lampiran pesan berhasil dibuat',
                'data' => new MessageAttachmentResource($messageAttachment)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat lampiran pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MessageAttachment $messageAttachment)
    {
        try {
            return new MessageAttachmentResource($messageAttachment);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data lampiran pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MessageAttachment $messageAttachment)
    {
        try {
            $validatedData = $request->validate([
                'pesan_id' => 'sometimes|required|exists:chat_pesan_chat,id',
                'tipe_lampiran' => 'sometimes|required|in:IMAGE,VIDEO,AUDIO,DOCUMENT,FILE',
                'url_lampiran' => 'sometimes|required|url|max:2048',
                'nama_file' => 'sometimes|required|string|max:255',
                'ukuran_file' => 'nullable|integer',
                'tipe_file' => 'nullable|string|max:100',
                'deskripsi' => 'nullable|string|max:500',
                'is_inline' => 'nullable|boolean',
            ]);

            $messageAttachment->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Lampiran pesan berhasil diperbarui',
                'data' => new MessageAttachmentResource($messageAttachment)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui lampiran pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MessageAttachment $messageAttachment)
    {
        try {
            $messageAttachment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Lampiran pesan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus lampiran pesan: ' . $e->getMessage()
            ], 500);
        }
    }
}