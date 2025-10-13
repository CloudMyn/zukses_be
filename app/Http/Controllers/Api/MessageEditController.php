<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MessageEdit;
use App\Http\Resources\MessageEditResource;
use Illuminate\Http\Request;

class MessageEditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $messageEdits = MessageEdit::paginate(15);
            return MessageEditResource::collection($messageEdits);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data edit pesan: ' . $e->getMessage()
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
                'isi_sebelumnya' => 'required|string|max:1000',
                'isi_baru' => 'required|string|max:1000',
                'waktu_edit' => 'nullable|date',
                'editor_user_id' => 'required|exists:users,id',
                'catatan' => 'nullable|string|max:500',
            ]);

            $messageEdit = MessageEdit::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Edit pesan berhasil dibuat',
                'data' => new MessageEditResource($messageEdit)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat edit pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MessageEdit $messageEdit)
    {
        try {
            return new MessageEditResource($messageEdit);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data edit pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MessageEdit $messageEdit)
    {
        try {
            $validatedData = $request->validate([
                'pesan_id' => 'sometimes|required|exists:chat_pesan_chat,id',
                'isi_sebelumnya' => 'sometimes|required|string|max:1000',
                'isi_baru' => 'sometimes|required|string|max:1000',
                'waktu_edit' => 'nullable|date',
                'editor_user_id' => 'sometimes|required|exists:users,id',
                'catatan' => 'nullable|string|max:500',
            ]);

            $messageEdit->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Edit pesan berhasil diperbarui',
                'data' => new MessageEditResource($messageEdit)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui edit pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MessageEdit $messageEdit)
    {
        try {
            $messageEdit->delete();

            return response()->json([
                'success' => true,
                'message' => 'Edit pesan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus edit pesan: ' . $e->getMessage()
            ], 500);
        }
    }
}