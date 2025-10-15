<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_produk', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('id_seller');
            $table->unsignedBigInteger('id_admin')->nullable();
            $table->string('sku')->unique();
            $table->string('nama_produk');
            $table->string('slug_produk')->unique();
            $table->text('deskripsi_lengkap')->nullable();
            $table->enum('kondisi_produk', ['BARU', 'BEKAS', 'REFURBISHED'])->default('BARU');
            $table->enum('status_produk', ['DRAFT', 'AKTIF', 'TIDAK_AKTIF', 'HAPUS', 'DITOLAK'])->default('DRAFT');
            $table->decimal('berat_paket', 8, 2)->nullable(); // dalam gram
            $table->integer('panjang_paket')->nullable(); // dalam cm
            $table->integer('lebar_paket')->nullable(); // dalam cm
            $table->integer('tinggi_paket')->nullable(); // dalam cm
            $table->decimal('harga_minimum', 12, 2)->default(0);
            $table->decimal('harga_maximum', 12, 2)->default(0);
            $table->integer('jumlah_stok')->default(0);
            $table->integer('stok_minimum')->default(0); // minimum stock alert
            $table->integer('jumlah_terjual')->default(0);
            $table->integer('jumlah_dilihat')->default(0);
            $table->integer('jumlah_difavoritkan')->default(0);
            $table->decimal('rating_produk', 3, 2)->default(0);
            $table->integer('jumlah_ulasan')->default(0);
            $table->boolean('is_produk_unggulan')->default(false);
            $table->boolean('is_produk_preorder')->default(false);
            $table->boolean('is_cod')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_product_varian')->default(false);
            $table->integer('waktu_preorder')->nullable(); // dalam hari
            $table->text('garansi_produk')->nullable();
            $table->string('etalase_kategori')->nullable(); // kategori etalase seller
            $table->json('tag_produk')->nullable(); // untuk SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('video_produk')->nullable();
            $table->timestamp('tanggal_dipublikasikan')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();

            $table->foreign('id_seller')->references('id')->on('tb_penjual')->onDelete('cascade');
            $table->foreign('id_admin')->references('id')->on('users')->onDelete('set null');

            $table->index(['id_seller', 'status_produk']);
            $table->index(['status_produk', 'is_produk_unggulan']);
            $table->index(['rating_produk', 'jumlah_ulasan']);
            $table->index(['jumlah_dilihat', 'jumlah_terjual']);
            $table->index('nama_produk');
            $table->index('deskripsi_lengkap');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_produk');
    }
};
