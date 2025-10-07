<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'name',
        'description',
        'price',
        'cost_price',
        'sku',
        'barcode',
        'stock',
        'min_stock',
        'category',
        'image',
        'is_active',
        'is_available',
        'attributes'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'is_active' => 'boolean',
        'is_available' => 'boolean',
        'attributes' => 'array'
    ];

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include available products (active and in stock).
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)
                    ->where('is_available', true)
                    ->where('stock', '>', 0);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to search in name and description.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%')
              ->orWhere('sku', 'like', '%' . $search . '%')
              ->orWhere('barcode', 'like', '%' . $search . '%');
        });
    }

    /**
     * Check if product is low stock.
     */
    public function getIsLowStockAttribute()
    {
        return $this->stock <= $this->min_stock;
    }

    /**
     * Get profit margin.
     */
    public function getProfitMarginAttribute()
    {
        if (!$this->cost_price || $this->cost_price == 0) {
            return 0;
        }
        
        return (($this->price - $this->cost_price) / $this->cost_price) * 100;
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get formatted cost price.
     */
    public function getFormattedCostPriceAttribute()
    {
        return $this->cost_price ? 'Rp ' . number_format($this->cost_price, 0, ',', '.') : '-';
    }

    /**
     * Update stock quantity.
     */
    public function updateStock($quantity)
    {
        $this->stock += $quantity;
        
        // Auto update availability based on stock
        if ($this->stock <= 0) {
            $this->is_available = false;
        } elseif (!$this->is_available) {
            $this->is_available = true;
        }
        
        $this->save();
    }
}