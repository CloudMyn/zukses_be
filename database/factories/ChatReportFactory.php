<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatReport>
 */
class ChatReportFactory extends Factory
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
            'id_pelapor' => $this->faker->numberBetween(1, 100), // Assuming there are 100 users
            'id_pelanggar' => $this->faker->numberBetween(1, 100), // Assuming there are 100 users
            'jenis_pelanggaran' => $this->faker->randomElement(['SPAM', 'KONTEN_TIDAK_SESUAI', 'PESAN_MENJIJIKKAN', 'PELECEHAN', 'LAINNYA']),
            'deskripsi_pelanggaran' => $this->faker->sentence,
            'bukti_pelanggaran' => $this->faker->optional(0.7)->url,
            'status_laporan' => $this->faker->randomElement(['DRAFT', 'DITERIMA', 'DITINJAU', 'DITUTUP', 'DIBERKASAKAN']),
            'tanggal_laporan' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'tanggal_review' => $this->faker->optional(0.5)->dateTimeBetween('-1 month', 'now'),
            'catatan_review' => $this->faker->optional(0.3)->sentence,
            'id_admin_reviewer' => $this->faker->optional(0.5)->numberBetween(1, 10), // Assuming there are 10 admin users
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}