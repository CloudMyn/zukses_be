<?php

namespace Database\Seeders;

use App\Models\CategoryProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing categories
        CategoryProduct::query()->delete();

        // Define category structure for Indonesian marketplace
        $categories = [
            // Electronics
            [
                'nama_kategori' => 'Elektronik',
                'slug_kategori' => 'elektronik',
                'deskripsi_kategori' => 'Semua kebutuhan elektronik dan gadget terbaru dengan harga terbaik',
                'gambar_kategori' => 'categories/electronics.jpg',
                'icon_kategori' => 'categories/icons/electronics.svg',
                'level_kategori' => 0,
                'urutan_tampilan' => 1,
                'is_kategori_featured' => true,
                'meta_title' => 'Elektronik - Beli Gadget & Peralatan Elektronik',
                'meta_description' => 'Jual berbagai macam elektronik dan gadget dengan harga murah dan berkualitas',
                'children' => [
                    [
                        'nama_kategori' => 'Smartphone & Aksesoris',
                        'slug_kategori' => 'smartphone-aksesoris',
                        'deskripsi_kategori' => 'Berbagai merk smartphone dan aksesoris pendukungnya',
                        'urutan_tampilan' => 1,
                        'is_kategori_featured' => true,
                        'children' => [
                            [
                                'nama_kategori' => 'Android Smartphone',
                                'slug_kategori' => 'android-smartphone',
                                'urutan_tampilan' => 1,
                            ],
                            [
                                'nama_kategori' => 'iPhone & iOS',
                                'slug_kategori' => 'iphone-ios',
                                'urutan_tampilan' => 2,
                            ],
                            [
                                'nama_kategori' => 'Aksesoris Handphone',
                                'slug_kategori' => 'aksesoris-handphone',
                                'urutan_tampilan' => 3,
                            ],
                        ]
                    ],
                    [
                        'nama_kategori' => 'Laptop & Komputer',
                        'slug_kategori' => 'laptop-komputer',
                        'deskripsi_kategori' => 'Laptop, PC, dan berbagai komponen komputer',
                        'urutan_tampilan' => 2,
                        'children' => [
                            [
                                'nama_kategori' => 'Laptop',
                                'slug_kategori' => 'laptop',
                                'urutan_tampilan' => 1,
                            ],
                            [
                                'nama_kategori' => 'PC Desktop',
                                'slug_kategori' => 'pc-desktop',
                                'urutan_tampilan' => 2,
                            ],
                            [
                                'nama_kategori' => 'Komponen Komputer',
                                'slug_kategori' => 'komponen-komputer',
                                'urutan_tampilan' => 3,
                            ],
                        ]
                    ],
                ]
            ],
            // Fashion
            [
                'nama_kategori' => 'Fashion',
                'slug_kategori' => 'fashion',
                'deskripsi_kategori' => 'Trend fashion terkini untuk pria, wanita, dan anak-anak',
                'gambar_kategori' => 'categories/fashion.jpg',
                'icon_kategori' => 'categories/icons/fashion.svg',
                'level_kategori' => 0,
                'urutan_tampilan' => 2,
                'is_kategori_featured' => true,
                'meta_title' => 'Fashion - Pakaian & Aksesoris Trendy',
                'meta_description' => 'Jual pakaian pria, wanita, dan anak dengan model terkini',
                'children' => [
                    [
                        'nama_kategori' => 'Fashion Wanita',
                        'slug_kategori' => 'fashion-wanita',
                        'deskripsi_kategori' => 'Pakaian dan aksesoris fashion untuk wanita',
                        'urutan_tampilan' => 1,
                        'is_kategori_featured' => true,
                        'children' => [
                            [
                                'nama_kategori' => 'Atasan Wanita',
                                'slug_kategori' => 'atasan-wanita',
                                'urutan_tampilan' => 1,
                            ],
                            [
                                'nama_kategori' => 'Bawahan Wanita',
                                'slug_kategori' => 'bawahan-wanita',
                                'urutan_tampilan' => 2,
                            ],
                            [
                                'nama_kategori' => 'Dress & Gaun',
                                'slug_kategori' => 'dress-gaun',
                                'urutan_tampilan' => 3,
                            ],
                            [
                                'nama_kategori' => 'Hijab & Muslim Fashion',
                                'slug_kategori' => 'hijab-muslim-fashion',
                                'urutan_tampilan' => 4,
                            ],
                        ]
                    ],
                    [
                        'nama_kategori' => 'Fashion Pria',
                        'slug_kategori' => 'fashion-pria',
                        'deskripsi_kategori' => 'Pakaian dan aksesoris fashion untuk pria',
                        'urutan_tampilan' => 2,
                        'children' => [
                            [
                                'nama_kategori' => 'Atasan Pria',
                                'slug_kategori' => 'atasan-pria',
                                'urutan_tampilan' => 1,
                            ],
                            [
                                'nama_kategori' => 'Bawahan Pria',
                                'slug_kategori' => 'bawahan-pria',
                                'urutan_tampilan' => 2,
                            ],
                            [
                                'nama_kategori' => 'Pakaian Muslim Pria',
                                'slug_kategori' => 'pakaian-muslim-pria',
                                'urutan_tampilan' => 3,
                            ],
                        ]
                    ],
                ]
            ],
            // Makanan & Minuman
            [
                'nama_kategori' => 'Makanan & Minuman',
                'slug_kategori' => 'makanan-minuman',
                'deskripsi_kategori' => 'Makanan, minuman, dan bahan makanan segar dengan kualitas terjamin',
                'gambar_kategori' => 'categories/food-beverage.jpg',
                'icon_kategori' => 'categories/icons/food-beverage.svg',
                'level_kategori' => 0,
                'urutan_tampilan' => 3,
                'is_kategori_featured' => true,
                'meta_title' => 'Makanan & Minuman - Segar dan Berkualitas',
                'meta_description' => 'Jual berbagai makanan, minuman, dan bahan makanan segar',
                'children' => [
                    [
                        'nama_kategori' => 'Makanan',
                        'slug_kategori' => 'makanan',
                        'deskripsi_kategori' => 'Berbagai jenis makanan siap saji dan frozen food',
                        'urutan_tampilan' => 1,
                        'children' => [
                            [
                                'nama_kategori' => 'Makanan Siap Saji',
                                'slug_kategori' => 'makanan-siap-saji',
                                'urutan_tampilan' => 1,
                            ],
                            [
                                'nama_kategori' => 'Snack & Camilan',
                                'slug_kategori' => 'snack-camilan',
                                'urutan_tampilan' => 2,
                            ],
                            [
                                'nama_kategori' => 'Kue & Roti',
                                'slug_kategori' => 'kue-roti',
                                'urutan_tampilan' => 3,
                            ],
                        ]
                    ],
                    [
                        'nama_kategori' => 'Minuman',
                        'slug_kategori' => 'minuman',
                        'deskripsi_kategori' => 'Berbagai jenis minuman segar dan kesehatan',
                        'urutan_tampilan' => 2,
                        'children' => [
                            [
                                'nama_kategori' => 'Kopi & Teh',
                                'slug_kategori' => 'kopi-teh',
                                'urutan_tampilan' => 1,
                            ],
                            [
                                'nama_kategori' => 'Jus & Minuman Segar',
                                'slug_kategori' => 'jus-minuman-segar',
                                'urutan_tampilan' => 2,
                            ],
                            [
                                'nama_kategori' => 'Susu & Produk Susu',
                                'slug_kategori' => 'susu-produk-susu',
                                'urutan_tampilan' => 3,
                            ],
                        ]
                    ],
                ]
            ],
            // Kesehatan & Kecantikan
            [
                'nama_kategori' => 'Kesehatan & Kecantikan',
                'slug_kategori' => 'kesehatan-kecantikan',
                'deskripsi_kategori' => 'Produk kesehatan dan kecantikan untuk perawatan tubuh',
                'gambar_kategori' => 'categories/health-beauty.jpg',
                'icon_kategori' => 'categories/icons/health-beauty.svg',
                'level_kategori' => 0,
                'urutan_tampilan' => 4,
                'is_kategori_featured' => false,
                'meta_title' => 'Kesehatan & Kecantikan - Produk Perawatan Terpercaya',
                'meta_description' => 'Jual produk kesehatan dan kecantikan dengan harga terjangkau',
                'children' => [
                    [
                        'nama_kategori' => 'Perawatan Wajah',
                        'slug_kategori' => 'perawatan-wajah',
                        'deskripsi_kategori' => 'Produk perawatan wajah untuk semua jenis kulit',
                        'urutan_tampilan' => 1,
                    ],
                    [
                        'nama_kategori' => 'Perawatan Tubuh',
                        'slug_kategori' => 'perawatan-tubuh',
                        'deskripsi_kategori' => 'Produk perawatan tubuh dan spa',
                        'urutan_tampilan' => 2,
                    ],
                    [
                        'nama_kategori' => 'Suplemen Kesehatan',
                        'slug_kategori' => 'suplemen-kesehatan',
                        'deskripsi_kategori' => 'Suplemen dan vitamin untuk kesehatan',
                        'urutan_tampilan' => 3,
                    ],
                ]
            ],
            // Rumah Tangga
            [
                'nama_kategori' => 'Rumah Tangga',
                'slug_kategori' => 'rumah-tangga',
                'deskripsi_kategori' => 'Peralatan dan perlengkapan rumah tangga untuk kebutuhan sehari-hari',
                'gambar_kategori' => 'categories/home-living.jpg',
                'icon_kategori' => 'categories/icons/home-living.svg',
                'level_kategori' => 0,
                'urutan_tampilan' => 5,
                'is_kategori_featured' => false,
                'meta_title' => 'Rumah Tangga - Perlengkapan Rumah Tangga Lengkap',
                'meta_description' => 'Jual berbagai perlengkapan rumah tangga dengan harga murah',
                'children' => [
                    [
                        'nama_kategori' => 'Dapur & Masak',
                        'slug_kategori' => 'dapur-masak',
                        'deskripsi_kategori' => 'Peralatan masak dan perlengkapan dapur',
                        'urutan_tampilan' => 1,
                    ],
                    [
                        'nama_kategori' => 'Kamar Mandi',
                        'slug_kategori' => 'kamar-mandi',
                        'deskripsi_kategori' => 'Perlengkapan kamar mandi dan perawatan',
                        'urutan_tampilan' => 2,
                    ],
                    [
                        'nama_kategori' => 'Perabotan',
                        'slug_kategori' => 'perabotan',
                        'deskripsi_kategori' => 'Furniture dan perabotan rumah',
                        'urutan_tampilan' => 3,
                    ],
                ]
            ],
        ];

        $this->createCategories($categories);
    }

    private function createCategories(array $categories, int $parentId = null, int $level = 0): void
    {
        foreach ($categories as $index => $categoryData) {
            $data = [
                'nama_kategori' => $categoryData['nama_kategori'],
                'slug_kategori' => $categoryData['slug_kategori'],
                'deskripsi_kategori' => $categoryData['deskripsi_kategori'] ?? null,
                'gambar_kategori' => $categoryData['gambar_kategori'] ?? null,
                'icon_kategori' => $categoryData['icon_kategori'] ?? null,
                'id_kategori_induk' => $parentId,
                'level_kategori' => $level,
                'urutan_tampilan' => $categoryData['urutan_tampilan'] ?? ($index + 1),
                'is_kategori_aktif' => true,
                'is_kategori_featured' => $categoryData['is_kategori_featured'] ?? false,
                'meta_title' => $categoryData['meta_title'] ?? null,
                'meta_description' => $categoryData['meta_description'] ?? null,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ];

            $category = CategoryProduct::create($data);

            // Create children if exist
            if (isset($categoryData['children']) && is_array($categoryData['children'])) {
                $this->createCategories($categoryData['children'], $category->id, $level + 1);
            }
        }
    }
}