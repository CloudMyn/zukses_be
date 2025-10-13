<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MessageAttachment>
 */
class MessageAttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_pesan' => $this->faker->numberBetween(1, 500), // Assuming there are 500 messages
            'nama_file' => $this->faker->word . '.' . $this->faker->fileExtension,
            'path_file' => 'attachments/' . $this->faker->uuid . '.' . $this->faker->fileExtension,
            'url_file' => $this->faker->url,
            'jenis_file' => $this->faker->randomElement(['GAMBAR', 'VIDEO', 'AUDIO', 'DOKUMEN', 'LAINNYA']),
            'ukuran_file' => $this->faker->numberBetween(1000, 10000000), // 1KB to 10MB
            'mime_type' => $this->faker->randomElement(['image/jpeg', 'image/png', 'video/mp4', 'audio/mpeg', 'application/pdf']),
            'deskripsi_file' => $this->faker->optional(0.5)->sentence,
            'is_thumbnail' => $this->faker->boolean,
            'urutan_tampilan' => $this->faker->numberBetween(1, 10),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}