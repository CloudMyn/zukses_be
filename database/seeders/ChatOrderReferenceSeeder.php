<?php

namespace Database\Seeders;

use App\Models\ChatMessage;
use App\Models\ChatOrderReference;
use App\Models\Order;
use Illuminate\Database\Seeder;

class ChatOrderReferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get sample records for relationships
        $sampleMessage = ChatMessage::first();
        $sampleOrder = Order::first();
        
        if (!$sampleMessage || !$sampleOrder) {
            return; // Exit if required relationships don't exist
        }
        
        // Sample data for chat order references
        $references = [
            [
                'pesan_id' => $sampleMessage->id,
                'order_id' => $sampleOrder->id,
                'marketplace_order_id' => 'ORD-XYZ-456',
                'snapshot' => json_encode([
                    'nomor_pesanan' => $sampleOrder->nomor_pesanan,
                    'total_pembayaran' => $sampleOrder->total_pembayaran,
                    'status_pesanan' => $sampleOrder->status_pesanan,
                    'item_count' => $sampleOrder->total_items,
                ]),
            ],
        ];

        foreach ($references as $reference) {
            ChatOrderReference::create([
                'pesan_id' => $reference['pesan_id'],
                'order_id' => $reference['order_id'],
                'marketplace_order_id' => $reference['marketplace_order_id'],
                'snapshot' => $reference['snapshot'],
            ]);
        }
    }
}