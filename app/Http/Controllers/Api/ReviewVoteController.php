<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReviewVote;
use App\Http\Resources\ReviewVoteResource;
use Illuminate\Http\Request;

class ReviewVoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $reviewVotes = ReviewVote::paginate(15);
            return ReviewVoteResource::collection($reviewVotes);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data suara ulasan: ' . $e->getMessage()
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
                'id_user' => 'required|exists:users,id',
                'tipe_vote' => 'required|in:SUKA,TIDAK_SUKA',
                'direaksi_pada' => 'nullable|date',
            ]);

            $reviewVote = ReviewVote::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Suara ulasan berhasil dibuat',
                'data' => new ReviewVoteResource($reviewVote)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat suara ulasan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ReviewVote $reviewVote)
    {
        try {
            return new ReviewVoteResource($reviewVote);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data suara ulasan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReviewVote $reviewVote)
    {
        try {
            $validatedData = $request->validate([
                'id_review' => 'sometimes|required|exists:ulasan_produk,id',
                'id_user' => 'sometimes|required|exists:users,id',
                'tipe_vote' => 'sometimes|required|in:SUKA,TIDAK_SUKA',
                'direaksi_pada' => 'nullable|date',
            ]);

            $reviewVote->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Suara ulasan berhasil diperbarui',
                'data' => new ReviewVoteResource($reviewVote)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui suara ulasan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReviewVote $reviewVote)
    {
        try {
            $reviewVote->delete();

            return response()->json([
                'success' => true,
                'message' => 'Suara ulasan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus suara ulasan: ' . $e->getMessage()
            ], 500);
        }
    }
}