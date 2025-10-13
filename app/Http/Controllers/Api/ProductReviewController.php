<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Http\Resources\ProductReviewResource;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $productReviews = ProductReview::paginate(15);
            return ProductReviewResource::collection($productReviews);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data ulasan produk: ' . $e->getMessage()
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
                'id_produk' => 'required|exists:tb_produk,id',
                'id_harga_varian' => 'nullable|exists:harga_varian_produk,id',
                'id_pembeli' => 'required|exists:users,id',
                'id_pesanan' => 'required|exists:pesanan,id',
                'rating_produk' => 'required|integer|min:1|max:5',
                'rating_akurasi_produk' => 'required|integer|min:1|max:5',
                'rating_kualitas_produk' => 'required|integer|min:1|max:5',
                'rating_pengiriman_produk' => 'required|integer|min:1|max:5',
                'komentar_ulasan' => 'nullable|string|max:1000',
                'is_ulasan_anonim' => 'required|boolean',
                'is_ulasan_terverifikasi' => 'required|boolean',
                'is_ditampilkan' => 'required|boolean',
                'jumlah_suka' => 'required|integer|min:0',
                'id_review_parent' => 'nullable|exists:ulasan_produk,id',
                'tanggal_ulasan' => 'nullable|date',
            ]);

            $productReview = ProductReview::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Ulasan produk berhasil dibuat',
                'data' => new ProductReviewResource($productReview)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat ulasan produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductReview $productReview)
    {
        try {
            return new ProductReviewResource($productReview);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data ulasan produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductReview $productReview)
    {
        try {
            $validatedData = $request->validate([
                'id_produk' => 'sometimes|required|exists:tb_produk,id',
                'id_harga_varian' => 'nullable|exists:harga_varian_produk,id',
                'id_pembeli' => 'sometimes|required|exists:users,id',
                'id_pesanan' => 'sometimes|required|exists:pesanan,id',
                'rating_produk' => 'sometimes|required|integer|min:1|max:5',
                'rating_akurasi_produk' => 'sometimes|required|integer|min:1|max:5',
                'rating_kualitas_produk' => 'sometimes|required|integer|min:1|max:5',
                'rating_pengiriman_produk' => 'sometimes|required|integer|min:1|max:5',
                'komentar_ulasan' => 'nullable|string|max:1000',
                'is_ulasan_anonim' => 'sometimes|required|boolean',
                'is_ulasan_terverifikasi' => 'sometimes|required|boolean',
                'is_ditampilkan' => 'sometimes|required|boolean',
                'jumlah_suka' => 'sometimes|required|integer|min:0',
                'id_review_parent' => 'nullable|exists:ulasan_produk,id',
                'tanggal_ulasan' => 'nullable|date',
            ]);

            $productReview->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Ulasan produk berhasil diperbarui',
                'data' => new ProductReviewResource($productReview)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui ulasan produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductReview $productReview)
    {
        try {
            $productReview->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ulasan produk berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus ulasan produk: ' . $e->getMessage()
            ], 500);
        }
    }
}