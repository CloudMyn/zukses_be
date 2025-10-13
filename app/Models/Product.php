<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tb_produk';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'id_seller',
        'id_admin',
        'sku',
        'nama_produk',
        'slug_produk',
        'deskripsi_lengkap',
        'kondisi_produk',
        'status_produk',
        'berat_paket',
        'panjang_paket',
        'lebar_paket',
        'tinggi_paket',
        'harga_minimum',
        'harga_maximum',
        'jumlah_stok',
        'stok_minimum',
        'jumlah_terjual',
        'jumlah_dilihat',
        'jumlah_difavoritkan',
        'rating_produk',
        'jumlah_ulasan',
        'is_produk_unggulan',
        'is_produk_preorder',
        'is_cod',
        'is_approved',
        'is_product_varian',
        'waktu_preorder',
        'garansi_produk',
        'etalase_kategori',
        'tag_produk',
        'meta_title',
        'meta_description',
        'video_produk',
        'tanggal_dipublikasikan',
    ];

    protected $casts = [
        'id_seller' => 'integer',
        'id_admin' => 'integer',
        'berat_paket' => 'decimal:2',
        'panjang_paket' => 'integer',
        'lebar_paket' => 'integer',
        'tinggi_paket' => 'integer',
        'harga_minimum' => 'decimal:2',
        'harga_maximum' => 'decimal:2',
        'jumlah_stok' => 'integer',
        'stok_minimum' => 'integer',
        'jumlah_terjual' => 'integer',
        'jumlah_dilihat' => 'integer',
        'jumlah_difavoritkan' => 'integer',
        'rating_produk' => 'decimal:2',
        'jumlah_ulasan' => 'integer',
        'is_produk_unggulan' => 'boolean',
        'is_produk_preorder' => 'boolean',
        'is_cod' => 'boolean',
        'is_approved' => 'boolean',
        'is_product_varian' => 'boolean',
        'waktu_preorder' => 'integer',
        'tag_produk' => 'array',
        'tanggal_dipublikasikan' => 'datetime',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    protected $dates = [
        'tanggal_dipublikasikan',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $attributes = [
        'kondisi_produk' => 'BARU',
        'status_produk' => 'DRAFT',
        'harga_minimum' => 0,
        'harga_maximum' => 0,
        'jumlah_stok' => 0,
        'stok_minimum' => 0,
        'jumlah_terjual' => 0,
        'jumlah_dilihat' => 0,
        'jumlah_difavoritkan' => 0,
        'rating_produk' => 0,
        'jumlah_ulasan' => 0,
        'is_produk_unggulan' => false,
        'is_produk_preorder' => false,
        'is_cod' => false,
        'is_approved' => false,
        'is_product_varian' => false,
    ];

    // Relationships
    public function seller()
    {
        return $this->belongsTo(Seller::class, 'id_seller', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin', 'id');
    }

    public function category()
    {
        return $this->belongsTo(CategoryProduct::class, 'id_kategori_produk', 'id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'id_produk', 'id')
            ->orderBy('urutan_gambar', 'asc');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class, 'id_produk', 'id')
            ->where('is_gambar_utama', true)
            ->orWhere('is_gambar_utama', function ($query) {
                $query->where('is_gambar_utama', true);
            })
            ->orderBy('urutan_gambar', 'asc');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'produk_id', 'id');
    }

    public function shippingInfo()
    {
        return $this->hasOne(ProductShippingInfo::class, 'id_produk', 'id');
    }

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class, 'id_produk', 'id')
            ->orderBy('dibuat_pada', 'desc');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'id_produk', 'id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'id_produk', 'id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status_produk', 'AKTIF')
            ->where('is_approved', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('status_produk', 'DRAFT');
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('tanggal_dipublikasikan');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_produk_unggulan', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('jumlah_stok', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('jumlah_stok <= stok_minimum')
            ->where('jumlah_stok', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('jumlah_stok', 0);
    }

    public function scopePreorder($query)
    {
        return $query->where('is_produk_preorder', true);
    }

    public function scopeCodAvailable($query)
    {
        return $query->where('is_cod', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('id_kategori_produk', $categoryId);
    }

    public function scopeBySeller($query, $sellerId)
    {
        return $query->where('id_seller', $sellerId);
    }

    public function scopeByPriceRange($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where('harga_minimum', '>=', $min);
        }
        if ($max !== null) {
            $query->where('harga_maximum', '<=', $max);
        }
        return $query;
    }

    public function scopeByCondition($query, $condition)
    {
        return $query->where('kondisi_produk', $condition);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama_produk', 'LIKE', "%{$search}%")
                ->orWhere('sku', 'LIKE', "%{$search}%")
                ->orWhere('deskripsi_lengkap', 'LIKE', "%{$search}%");
        });
    }

    public function scopeOrderByRating($query, $direction = 'desc')
    {
        return $query->orderBy('rating_produk', $direction)
            ->orderBy('jumlah_ulasan', $direction);
    }

    public function scopeOrderByPopularity($query, $direction = 'desc')
    {
        return $query->orderBy('jumlah_dilihat', $direction)
            ->orderBy('jumlah_terjual', $direction);
    }

    public function scopeOrderByPrice($query, $direction = 'asc')
    {
        return $query->orderBy('harga_minimum', $direction);
    }

    public function scopeOrderByNewest($query)
    {
        return $query->orderBy('dibuat_pada', 'desc');
    }

    public function scopeOrderByOldest($query)
    {
        return $query->orderBy('dibuat_pada', 'asc');
    }

    // Accessors & Mutators
    public function getNamaProdukAttribute($value)
    {
        return ucfirst(strtolower($value));
    }

    public function setNamaProdukAttribute($value)
    {
        $this->attributes['nama_produk'] = ucwords(strtolower($value));

        // Auto-generate slug if not provided
        if (empty($this->slug_produk)) {
            $this->attributes['slug_produk'] = $this->generateUniqueSlug($value);
        }
    }

    public function setSlugProdukAttribute($value)
    {
        $this->attributes['slug_produk'] = Str::slug($value);
    }

    public function getHargaRangeAttribute()
    {
        if ($this->harga_minimum == $this->harga_maximum) {
            return number_format($this->harga_minimum, 0, ',', '.');
        }
        return number_format($this->harga_minimum, 0, ',', '.') . ' - ' . number_format($this->harga_maximum, 0, ',', '.');
    }

    public function getHargaDisplayAttribute()
    {
        return number_format($this->harga_minimum, 0, ',', '.');
    }

    public function getDiskonDisplayAttribute()
    {
        if ($this->harga_maximum > $this->harga_minimum) {
            $discount = (($this->harga_maximum - $this->harga_minimum) / $this->harga_maximum) * 100;
            return round($discount, 0) . '%';
        }
        return 0;
    }

    public function getGambarUtamaAttribute()
    {
        return $this->primaryImage?->url_gambar ?? asset('images/product-placeholder.jpg');
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status_produk) {
            'DRAFT' => 'Draft',
            'AKTIF' => 'Aktif',
            'TIDAK_AKTIF' => 'Tidak Aktif',
            'HAPUS' => 'Dihapus',
            'DITOLAK' => 'Ditolak',
            default => $this->status_produk,
        };
    }

    public function getKondisiLabelAttribute()
    {
        return match ($this->kondisi_produk) {
            'BARU' => 'Baru',
            'BEKAS' => 'Bekas',
            'REFURBISHED' => 'Refurbished',
            default => $this->kondisi_produk,
        };
    }

    public function getIsInStockAttribute()
    {
        return $this->jumlah_stok > 0;
    }

    public function getIsLowStockAttribute()
    {
        return $this->jumlah_stok > 0 && $this->jumlah_stok <= $this->stok_minimum;
    }

    public function getStokStatusAttribute()
    {
        if ($this->jumlah_stok == 0) {
            return 'habis';
        } elseif ($this->is_low_stock) {
            return 'sedikit';
        }
        return 'tersedia';
    }

    public function getRatingStarsAttribute()
    {
        $stars = [];
        $fullStars = floor($this->rating_produk);
        $hasHalfStar = ($this->rating_produk - $fullStars) >= 0.5;

        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $fullStars) {
                $stars[] = 'full';
            } elseif ($i == $fullStars + 1 && $hasHalfStar) {
                $stars[] = 'half';
            } else {
                $stars[] = 'empty';
            }
        }

        return $stars;
    }

    public function getBeratFormattedAttribute()
    {
        if ($this->berat_paket >= 1000) {
            return ($this->berat_paket / 1000) . ' kg';
        }
        return $this->berat_paket . ' gram';
    }

    public function getDimensiDisplayAttribute()
    {
        if ($this->panjang_paket && $this->lebar_paket && $this->tinggi_paket) {
            return $this->panjang_paket . ' x ' . $this->lebar_paket . ' x ' . $this->tinggi_paket . ' cm';
        }
        return null;
    }

    // Helper Methods
    public function publish()
    {
        $this->status_produk = 'AKTIF';
        $this->tanggal_dipublikasikan = now();
        $this->save();
    }

    public function unpublish()
    {
        $this->status_produk = 'TIDAK_AKTIF';
        $this->save();
    }

    public function approve(User $admin = null)
    {
        $this->status_produk = 'AKTIF';
        $this->is_approved = true;
        $this->id_admin = $admin?->id;
        $this->save();
    }

    public function reject(User $admin = null, $reason = null)
    {
        $this->status_produk = 'DITOLAK';
        $this->is_approved = false;
        $this->id_admin = $admin?->id;
        $this->save();
    }

    public function addToStock($quantity, $reason = null, User $operator = null)
    {
        $oldStock = $this->jumlah_stok;
        $this->jumlah_stok += $quantity;
        $this->save();

        // Log inventory change
        InventoryLog::create([
            'id_produk' => $this->id,
            'tipe_transaksi' => 'MASUK',
            'jumlah_transaksi' => $quantity,
            'stok_sebelum' => $oldStock,
            'stok_sesudah' => $this->jumlah_stok,
            'alasan_transaksi' => $reason,
            'id_operator' => $operator?->id,
        ]);

        return $this;
    }

    public function removeFromStock($quantity, $reason = null, User $operator = null)
    {
        if ($quantity > $this->jumlah_stok) {
            throw new \Exception('Tidak dapat mengurangi stok lebih dari stok yang tersedia');
        }

        $oldStock = $this->jumlah_stok;
        $this->jumlah_stok -= $quantity;
        $this->save();

        // Log inventory change
        InventoryLog::create([
            'id_produk' => $this->id,
            'tipe_transaksi' => 'KELUAR',
            'jumlah_transaksi' => $quantity,
            'stok_sebelum' => $oldStock,
            'stok_sesudah' => $this->jumlah_stok,
            'alasan_transaksi' => $reason,
            'id_operator' => $operator?->id,
        ]);

        return $this;
    }

    public function recordView()
    {
        $this->increment('jumlah_dilihat');
        return $this;
    }

    public function recordSale($quantity = 1)
    {
        $this->increment('jumlah_terjual', $quantity);
        $this->decrement('jumlah_stok', $quantity);
        return $this;
    }

    public function toggleFavorite()
    {
        $this->increment('jumlah_difavoritkan');
        return $this;
    }

    public function updateRating($newRating)
    {
        // This would typically be called when a review is added/updated
        $this->update([
            'rating_produk' => $newRating,
            'jumlah_ulasan' => $this->reviews()->count(),
        ]);
        return $this;
    }

    public function generateUniqueSlug($name, $excludeId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (
            self::where('slug_produk', $slug)
                ->when($excludeId, function ($query, $excludeId) {
                    return $query->where('id', '!=', $excludeId);
                })
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function getRouteKeyName()
    {
        return 'slug_produk';
    }
}
