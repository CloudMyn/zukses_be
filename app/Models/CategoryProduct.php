<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tb_kategori_produk';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'nama_kategori',
        'slug_kategori',
        'deskripsi_kategori',
        'gambar_kategori',
        'icon_kategori',
        'id_kategori_induk',
        'level_kategori',
        'urutan_tampilan',
        'is_kategori_aktif',
        'is_kategori_featured',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'id_kategori_induk' => 'integer',
        'level_kategori' => 'integer',
        'urutan_tampilan' => 'integer',
        'is_kategori_aktif' => 'boolean',
        'is_kategori_featured' => 'boolean',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    protected $dates = [
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $attributes = [
        'level_kategori' => 0,
        'urutan_tampilan' => 0,
        'is_kategori_aktif' => true,
        'is_kategori_featured' => false,
    ];

    // Relationships
    public function parentCategory()
    {
        return $this->belongsTo(CategoryProduct::class, 'id_kategori_induk', 'id');
    }

    public function childCategories()
    {
        return $this->hasMany(CategoryProduct::class, 'id_kategori_induk', 'id')
                    ->orderBy('urutan_tampilan', 'asc');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'id_kategori_produk', 'id');
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class, 'id_kategori_produk', 'id')
                    ->where('status_produk', 'AKTIF');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_kategori_aktif', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_kategori_featured', true);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('id_kategori_induk');
    }

    public function scopeLevel($query, $level)
    {
        return $query->where('level_kategori', $level);
    }

    public function scopeOrderByOrder($query)
    {
        return $query->orderBy('urutan_tampilan', 'asc');
    }

    public function scopeOrderByLevel($query)
    {
        return $query->orderBy('level_kategori', 'asc')
                    ->orderBy('urutan_tampilan', 'asc');
    }

    // Accessors & Mutators
    public function getNamaKategoriAttribute($value)
    {
        return ucfirst(strtolower($value));
    }

    public function setNamaKategoriAttribute($value)
    {
        $this->attributes['nama_kategori'] = ucwords(strtolower($value));

        // Auto-generate slug if not provided
        if (empty($this->slug_kategori)) {
            $this->attributes['slug_kategori'] = str_slug($value);
        }
    }

    public function setSlugKategoriAttribute($value)
    {
        $this->attributes['slug_kategori'] = str_slug($value);
    }

    public function getFullImagePathAttribute()
    {
        if ($this->gambar_kategori) {
            return asset('storage/categories/' . $this->gambar_kategori);
        }
        return null;
    }

    public function getFullIconPathAttribute()
    {
        if ($this->icon_kategori) {
            return asset('storage/categories/icons/' . $this->icon_kategori);
        }
        return null;
    }

    public function getProductCountAttribute()
    {
        return $this->products()->count();
    }

    public function getActiveProductCountAttribute()
    {
        return $this->activeProducts()->count();
    }

    public function getBreadcrumbAttribute()
    {
        $breadcrumb = [];
        $category = $this;

        while ($category) {
            array_unshift($breadcrumb, [
                'id' => $category->id,
                'nama_kategori' => $category->nama_kategori,
                'slug_kategori' => $category->slug_kategori
            ]);
            $category = $category->parentCategory;
        }

        return $breadcrumb;
    }

    public function getIsRootAttribute()
    {
        return is_null($this->id_kategori_induk);
    }

    public function getHasChildrenAttribute()
    {
        return $this->childCategories()->count() > 0;
    }

    // Helper Methods
    public function hasChildren()
    {
        return $this->childCategories()->exists();
    }

    public function getLevelName()
    {
        switch ($this->level_kategori) {
            case 0:
                return 'Root Category';
            case 1:
                return 'Main Category';
            case 2:
                return 'Sub Category';
            case 3:
                return 'Sub Sub Category';
            default:
                return 'Level ' . $this->level_kategori;
        }
    }

    public function canBeDeleted()
    {
        // Check if category has products
        if ($this->products()->exists()) {
            return false;
        }

        // Check if category has children
        if ($this->hasChildren()) {
            return false;
        }

        return true;
    }

    public function getDeletionRestrictions()
    {
        $restrictions = [];

        if ($this->products()->exists()) {
            $restrictions[] = 'This category has ' . $this->products()->count() . ' product(s)';
        }

        if ($this->hasChildren()) {
            $restrictions[] = 'This category has ' . $this->childCategories()->count() . ' sub-categor(y)(ies)';
        }

        return $restrictions;
    }

    public static function generateUniqueSlug($nama, $excludeId = null)
    {
        $slug = str_slug($nama);
        $originalSlug = $slug;
        $counter = 1;

        while (self::where('slug_kategori', $slug)
                   ->when($excludeId, function($query, $excludeId) {
                       return $query->where('id', '!=', $excludeId);
                   })
                   ->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function getRouteKeyName()
    {
        return 'slug_kategori';
    }
}