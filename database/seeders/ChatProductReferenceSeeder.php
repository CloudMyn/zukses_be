<?php

namespace Database\Seeders;

use App\Models\ChatProductReference;
use App\Models\ChatMessage;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ChatProductReferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get sample records for relationships
        $sampleMessage = ChatMessage::first();
        $sampleProduct = Product::first();
        
        if (!$sampleMessage || !$sampleProduct) {
            return; // Exit if required relationships don't exist
        }
        
        // Sample data for chat product references
        $references = [
            [
                'pesan_id' => $sampleMessage->id,
                'product_id' => $sampleProduct->id,
                'marketplace_product_id' => 'PROD-ABC-123',
                'snapshot' => json_encode([
                    'nama_produk' => $sampleProduct->nama_produk,
                    'harga' => $sampleProduct->harga_minimum,
                    'gambar_produk' => 'https://example.com/products/sample.jpg',
                    'deskripsi_produk' => 'Deskripsi produk sample',
                ]),
            ],
        ];

        foreach ($references as $reference) {
            ChatProductReference::create([
                'pesan_id' => $reference['pesan_id'],
                'product_id' => $reference['product_id'],
                'marketplace_product_id' => $reference['marketplace_product_id'],
                'snapshot' => $reference['snapshot'],
            ]);
        }
    }
}