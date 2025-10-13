<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MessageStatus;
use App\Http\Resources\MessageStatusResource;
use Illuminate\Http\Request;

class MessageStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $messageStatuses = MessageStatus::paginate(15);
            return MessageStatusResource::collection($messageStatuses);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data status pesan: ' . $e->getMessage()
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
                'status' => 'required|in:SENT,DELIVERED,READ,FAILED',
                'status_pada' => 'nullable|date',
                'device_info' => 'nullable|json',
            ]);

            $messageStatus = MessageStatus::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Status pesan berhasil dibuat',
                'data' => new MessageStatusResource($messageStatus)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat status pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MessageStatus $messageStatus)
    {
        try {
            return new MessageStatusResource($messageStatus);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data status pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MessageStatus $messageStatus)
    {
        try {
            $validatedData = $request->validate([
                'pesan_id' => 'sometimes|required|exists:chat_pesan_chat,id',
                'user_id' => 'sometimes|required|exists:users,id',
                'status' => 'sometimes|required|in:SENT,DELIVERED,READ,FAILED',
                'status_pada' => 'nullable|date',
                'device_info' => 'nullable|json',
            ]);

            $messageStatus->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Status pesan berhasil diperbarui',
                'data' => new MessageStatusResource($messageStatus)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui status pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MessageStatus $messageStatus)
    {
        try {
            $messageStatus->delete();

            return response()->json([
                'success' => true,
                'message' => 'Status pesan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus status pesan: ' . $e->getMessage()
            ], 500);
        }
    }
}