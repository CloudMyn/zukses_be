<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SearchHistory;
use App\Http\Resources\SearchHistoryResource;
use Illuminate\Http\Request;

class SearchHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $searchHistories = SearchHistory::paginate(15);
            return SearchHistoryResource::collection($searchHistories);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data riwayat pencarian: ' . $e->getMessage()
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
                'id_user' => 'required|exists:users,id',
                'kata_pencarian' => 'required|string|max:255',
                'jumlah_hasil' => 'required|integer|min:0',
                'ip_address' => 'nullable|string|max:45',
                'dibuat_pada' => 'nullable|date',
            ]);

            $searchHistory = SearchHistory::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Riwayat pencarian berhasil dibuat',
                'data' => new SearchHistoryResource($searchHistory)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat riwayat pencarian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SearchHistory $searchHistory)
    {
        try {
            return new SearchHistoryResource($searchHistory);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data riwayat pencarian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SearchHistory $searchHistory)
    {
        try {
            $validatedData = $request->validate([
                'id_user' => 'sometimes|required|exists:users,id',
                'kata_pencarian' => 'sometimes|required|string|max:255',
                'jumlah_hasil' => 'sometimes|required|integer|min:0',
                'ip_address' => 'nullable|string|max:45',
                'dibuat_pada' => 'nullable|date',
            ]);

            $searchHistory->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Riwayat pencarian berhasil diperbarui',
                'data' => new SearchHistoryResource($searchHistory)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui riwayat pencarian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SearchHistory $searchHistory)
    {
        try {
            $searchHistory->delete();

            return response()->json([
                'success' => true,
                'message' => 'Riwayat pencarian berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus riwayat pencarian: ' . $e->getMessage()
            ], 500);
        }
    }
}