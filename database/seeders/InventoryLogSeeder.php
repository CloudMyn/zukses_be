<?php

namespace Database\Seeders;

use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\ProductVariantPrice;
use App\Models\User;
use Illuminate\Database\Seeder;

class InventoryLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get sample records for relationships
        $sampleProduct = Product::first();
        $sampleVariantPrice = ProductVariantPrice::first();
        $sampleUser = User::first();
        
        if (!$sampleProduct || !$sampleUser) {
            return; // Exit if required relationships don't exist
        }
        
        // Sample data for inventory logs
        $logs = [
            [
                'id_produk' => $sampleProduct->id,
                'id_harga_varian' => $sampleVariantPrice ? $sampleVariantPrice->id : null,
                'tipe_transaksi' => 'MASUK',
                'jumlah_transaksi' => 50,
                'stok_sebelum' => 0,
                'stok_sesudah' => 50,
                'alasan_transaksi' => 'Pembelian awal',
                'id_operator' => $sampleUser->id,
                'catatan_tambahan' => 'Stok awal produk pertama',
            ],
            [
                'id_produk' => $sampleProduct->id,
                'id_harga_varian' => $sampleVariantPrice ? $sampleVariantPrice->id : null,
                'tipe_transaksi' => 'KELUAR',
                'jumlah_transaksi' => 10,
                'stok_sebelum' => 50,
                'stok_sesudah' => 40,
                'alasan_transaksi' => 'Penjualan',
                'id_operator' => $sampleUser->id,
                'catatan_tambahan' => 'Penjualan produk pertama',
            ],
        ];

        foreach ($logs as $log) {
            InventoryLog::create([
                'id_produk' => $log['id_produk'],
                'id_harga_varian' => $log['id_harga_varian'],
                'tipe_transaksi' => $log['tipe_transaksi'],
                'jumlah_transaksi' => $log['jumlah_transaksi'],
                'stok_sebelum' => $log['stok_sebelum'],
                'stok_sesudah' => $log['stok_sesudah'],
                'alasan_transaksi' => $log['alasan_transaksi'],
                'id_operator' => $log['id_operator'],
                'catatan_tambahan' => $log['catatan_tambahan'],
            ]);
        }
    }
}