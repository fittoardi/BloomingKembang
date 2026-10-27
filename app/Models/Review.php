<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_item_id',
        'rating',
        'title',
        'comment',
        'is_approved',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_approved' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * Bootstrap model events to automatically recalculate product rating average and count.
     */
    protected static function booted(): void
    {
        static::saved(function (Review $review) {
            $review->recalculateProductRating();
        });

        static::deleted(function (Review $review) {
            $review->recalculateProductRating();
        });
    }

    public function recalculateProductRating(): void
    {
        $product = $this->product;

        if (! $product) {
            return;
        }

        $approvedReviews = $product->reviews()->where('is_approved', true);
        $count = $approvedReviews->count();
        $avg = $count > 0 ? round((float) $approvedReviews->avg('rating'), 1) : 0.0;

        $product->update([
            'rating_avg' => $avg,
            'rating_count' => $count,
        ]);
    }
}
