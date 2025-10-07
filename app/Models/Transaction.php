<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
 * @property string $transaction_id
 * @property float $total_amount
 * @property float $paid_amount
 * @property float $change_amount
 * @property string|array $items
 * @property \Carbon\Carbon $transaction_date
 */

    protected $fillable = [
        'user_id',
        'transaction_id',
        'total_amount',
        'paid_amount',
        'change_amount',
        'items',
        'transaction_date'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'items' => 'array',
        'transaction_date' => 'datetime'
    ];

    /**
     * New Relationships
     */
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create transaction items from cart
     */
    public function createItemsFromCart($cartItems)
    {
        foreach ($cartItems as $item) {
            $this->items()->create([
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'product_price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
                'product_data' => [
                    'category' => $item['category'] ?? null,
                    'image' => $item['image'] ?? null,
                    'original_data' => $item // backup all original data
                ]
            ]);
        }
    }

    /**
     * Get the formatted transaction date
     */
    public function getFormattedDateAttribute()
    {
        return $this->transaction_date->format('d/m/Y H:i:s');
    }

    /**
     * Scope a query to only include transactions on a specific date.
     */
    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('transaction_date', $date);
    }

    /**
     * Scope a query to only include transactions between dates.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }
}