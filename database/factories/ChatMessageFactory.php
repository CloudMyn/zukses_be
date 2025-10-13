<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatMessage>
 */
class ChatMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_obrolan' => $this->faker->numberBetween(1, 50), // Assuming there are 50 conversations
            'id_pengirim' => $this->faker->numberBetween(1, 100), // Assuming there are 100 users
            'isi_pesan' => $this->faker->sentence,
            'jenis_pesan' => $this->faker->randomElement(['TEKS', 'GAMBAR', 'VIDEO', 'DOKUMEN', 'LOKASI']),
            'id_pesan_induk' => $this->faker->optional(0.2)->numberBetween(1, 500), // 20% chance of being a reply
            'tanggal_pesan' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'dibaca_pada' => $this->faker->optional(0.7)->dateTimeBetween('-1 week', 'now'),
            'ditarik_pada' => $this->faker->optional(0.05)->dateTimeBetween('-1 week', 'now'), // 5% chance of withdrawn
            'jumlah_baca' => $this->faker->numberBetween(0, 50),
            'jumlah_diteruskan' => $this->faker->numberBetween(0, 10),
            'metadata' => json_encode([
                'file_info' => [
                    'name' => $this->faker->word . '.jpg',
                    'size' => $this->faker->numberBetween(1000, 1000000),
                    'mime_type' => 'image/jpeg'
                ]
            ]),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}