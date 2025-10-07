<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'product_name',
        'product_price',
        'quantity',
        'subtotal',
        'product_data'
    ];

    protected $casts = [
        'product_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'quantity' => 'integer',
        'product_data' => 'array'
    ];

    /**
     * Relationships
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope for sales reporting
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereHas('transaction', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('transaction_date', [$startDate, $endDate]);
        });
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->whereHas('product', function ($q) use ($category) {
            $q->where('category', $category);
        });
    }

    /**
     * Calculate total sales for this item
     */
    // public function getTotalSalesAttribute()
    // {
    //     return $this->quantity * $this->product_price;
    // }
}