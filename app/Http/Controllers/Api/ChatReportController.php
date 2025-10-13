<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatReport;
use App\Http\Resources\ChatReportResource;
use Illuminate\Http\Request;

class ChatReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $chatReports = ChatReport::paginate(15);
            return ChatReportResource::collection($chatReports);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data laporan chat: ' . $e->getMessage()
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
                'reporter_id' => 'required|exists:users,id',
                'alasan' => 'required|string|max:500',
                'metadata' => 'nullable|json',
            ]);

            $chatReport = ChatReport::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Laporan chat berhasil dibuat',
                'data' => new ChatReportResource($chatReport)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat laporan chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ChatReport $chatReport)
    {
        try {
            return new ChatReportResource($chatReport);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data laporan chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChatReport $chatReport)
    {
        try {
            $validatedData = $request->validate([
                'percakapan_id' => 'sometimes|required|exists:chat_percakapan,id',
                'reporter_id' => 'sometimes|required|exists:users,id',
                'alasan' => 'sometimes|required|string|max:500',
                'metadata' => 'nullable|json',
            ]);

            $chatReport->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Laporan chat berhasil diperbarui',
                'data' => new ChatReportResource($chatReport)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui laporan chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatReport $chatReport)
    {
        try {
            $chatReport->delete();

            return response()->json([
                'success' => true,
                'message' => 'Laporan chat berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus laporan chat: ' . $e->getMessage()
            ], 500);
        }
    }
}