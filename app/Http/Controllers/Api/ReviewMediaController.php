<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReviewMedia;
use App\Http\Resources\ReviewMediaResource;
use Illuminate\Http\Request;

class ReviewMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $reviewMedias = ReviewMedia::paginate(15);
            return ReviewMediaResource::collection($reviewMedias);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data media ulasan: ' . $e->getMessage()
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
                'id_review' => 'required|exists:ulasan_produk,id',
                'tipe_media' => 'required|in:GAMBAR,VIDEO',
                'url_media' => 'required|string|max:2048',
                'keterangan_media' => 'nullable|string|max:255',
                'urutan_media' => 'required|integer|min:0',
            ]);

            $reviewMedia = ReviewMedia::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Media ulasan berhasil dibuat',
                'data' => new ReviewMediaResource($reviewMedia)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat media ulasan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ReviewMedia $reviewMedia)
    {
        try {
            return new ReviewMediaResource($reviewMedia);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data media ulasan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReviewMedia $reviewMedia)
    {
        try {
            $validatedData = $request->validate([
                'id_review' => 'sometimes|required|exists:ulasan_produk,id',
                'tipe_media' => 'sometimes|required|in:GAMBAR,VIDEO',
                'url_media' => 'sometimes|required|string|max:2048',
                'keterangan_media' => 'nullable|string|max:255',
                'urutan_media' => 'sometimes|required|integer|min:0',
            ]);

            $reviewMedia->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Media ulasan berhasil diperbarui',
                'data' => new ReviewMediaResource($reviewMedia)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui media ulasan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReviewMedia $reviewMedia)
    {
        try {
            $reviewMedia->delete();

            return response()->json([
                'success' => true,
                'message' => 'Media ulasan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus media ulasan: ' . $e->getMessage()
            ], 500);
        }
    }
}