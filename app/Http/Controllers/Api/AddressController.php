<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Http\Resources\AddressResource;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $addresses = Address::paginate(15);
            return AddressResource::collection($addresses);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data alamat: ' . $e->getMessage()
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
                'label_alamat' => 'required|string|max:255',
                'nama_penerima' => 'required|string|max:255',
                'nomor_telepon_penerima' => 'required|string|max:20',
                'alamat_lengkap' => 'required|string',
                'id_provinsi' => 'required|integer',
                'nama_provinsi' => 'required|string|max:255',
                'id_kabupaten' => 'required|integer',
                'nama_kabupaten' => 'required|string|max:255',
                'id_kecamatan' => 'required|integer',
                'nama_kecamatan' => 'required|string|max:255',
                'id_kelurahan' => 'required|integer',
                'nama_kelurahan' => 'required|string|max:255',
                'kode_pos' => 'required|string|max:10',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'adalah_alamat_utama' => 'required|boolean',
                'tipe_alamat' => 'required|in:RUMAH,KANTOR,GUDANG,LAINNYA',
            ]);

            $address = Address::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil dibuat',
                'data' => new AddressResource($address)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat alamat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        try {
            return new AddressResource($address);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data alamat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Address $address)
    {
        try {
            $validatedData = $request->validate([
                'id_user' => 'sometimes|required|exists:users,id',
                'label_alamat' => 'sometimes|required|string|max:255',
                'nama_penerima' => 'sometimes|required|string|max:255',
                'nomor_telepon_penerima' => 'sometimes|required|string|max:20',
                'alamat_lengkap' => 'sometimes|required|string',
                'id_provinsi' => 'sometimes|required|integer',
                'nama_provinsi' => 'sometimes|required|string|max:255',
                'id_kabupaten' => 'sometimes|required|integer',
                'nama_kabupaten' => 'sometimes|required|string|max:255',
                'id_kecamatan' => 'sometimes|required|integer',
                'nama_kecamatan' => 'sometimes|required|string|max:255',
                'id_kelurahan' => 'sometimes|required|integer',
                'nama_kelurahan' => 'sometimes|required|string|max:255',
                'kode_pos' => 'sometimes|required|string|max:10',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'adalah_alamat_utama' => 'sometimes|required|boolean',
                'tipe_alamat' => 'sometimes|required|in:RUMAH,KANTOR,GUDANG,LAINNYA',
            ]);

            $address->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil diperbarui',
                'data' => new AddressResource($address)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui alamat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        try {
            $address->delete();

            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus alamat: ' . $e->getMessage()
            ], 500);
        }
    }
}