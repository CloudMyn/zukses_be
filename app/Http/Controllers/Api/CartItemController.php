<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Http\Resources\CartItemResource;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $cartItems = CartItem::paginate(15);
            return CartItemResource::collection($cartItems);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data item keranjang: ' . $e->getMessage()
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
                'id_cart' => 'required|exists:keranjang_belanja,id',
                'id_produk' => 'required|exists:tb_produk,id',
                'id_harga_varian' => 'nullable|exists:harga_varian_produk,id',
                'nama_produk' => 'required|string|max:255',
                'gambar_produk' => 'nullable|string|max:255',
                'harga_satuan' => 'required|numeric|min:0',
                'jumlah_pesanan' => 'required|integer|min:1',
                'subtotal_harga' => 'required|numeric|min:0',
                'berat_item' => 'required|numeric|min:0',
                'catatan_pesanan' => 'nullable|string|max:500',
                'is_item_aktif' => 'required|boolean',
            ]);

            $cartItem = CartItem::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Item keranjang berhasil dibuat',
                'data' => new CartItemResource($cartItem)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat item keranjang: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CartItem $cartItem)
    {
        try {
            return new CartItemResource($cartItem);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data item keranjang: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        try {
            $validatedData = $request->validate([
                'id_cart' => 'sometimes|required|exists:keranjang_belanja,id',
                'id_produk' => 'sometimes|required|exists:tb_produk,id',
                'id_harga_varian' => 'nullable|exists:harga_varian_produk,id',
                'nama_produk' => 'sometimes|required|string|max:255',
                'gambar_produk' => 'nullable|string|max:255',
                'harga_satuan' => 'sometimes|required|numeric|min:0',
                'jumlah_pesanan' => 'sometimes|required|integer|min:1',
                'subtotal_harga' => 'sometimes|required|numeric|min:0',
                'berat_item' => 'sometimes|required|numeric|min:0',
                'catatan_pesanan' => 'nullable|string|max:500',
                'is_item_aktif' => 'sometimes|required|boolean',
            ]);

            $cartItem->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Item keranjang berhasil diperbarui',
                'data' => new CartItemResource($cartItem)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui item keranjang: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $cartItem)
    {
        try {
            $cartItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item keranjang berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus item keranjang: ' . $e->getMessage()
            ], 500);
        }
    }
}