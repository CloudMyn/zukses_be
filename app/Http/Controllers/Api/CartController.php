<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Http\Resources\CartResource;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $carts = Cart::paginate(15);
            return CartResource::collection($carts);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data keranjang belanja: ' . $e->getMessage()
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
                'id_user' => 'nullable|exists:users,id',
                'session_id' => 'nullable|string|max:255',
                'id_seller' => 'required|exists:tb_penjual,id',
                'total_items' => 'required|integer|min:0',
                'total_berat' => 'required|numeric|min:0',
                'total_harga' => 'required|numeric|min:0',
                'total_diskon' => 'required|numeric|min:0',
                'is_cart_aktif' => 'required|boolean',
                'kadaluarsa_pada' => 'nullable|date',
            ]);

            $cart = Cart::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Keranjang belanja berhasil dibuat',
                'data' => new CartResource($cart)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat keranjang belanja: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        try {
            return new CartResource($cart);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data keranjang belanja: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        try {
            $validatedData = $request->validate([
                'id_user' => 'sometimes|nullable|exists:users,id',
                'session_id' => 'nullable|string|max:255',
                'id_seller' => 'sometimes|required|exists:tb_penjual,id',
                'total_items' => 'sometimes|required|integer|min:0',
                'total_berat' => 'sometimes|required|numeric|min:0',
                'total_harga' => 'sometimes|required|numeric|min:0',
                'total_diskon' => 'sometimes|required|numeric|min:0',
                'is_cart_aktif' => 'sometimes|required|boolean',
                'kadaluarsa_pada' => 'nullable|date',
            ]);

            $cart->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Keranjang belanja berhasil diperbarui',
                'data' => new CartResource($cart)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui keranjang belanja: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        try {
            $cart->delete();

            return response()->json([
                'success' => true,
                'message' => 'Keranjang belanja berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus keranjang belanja: ' . $e->getMessage()
            ], 500);
        }
    }
}