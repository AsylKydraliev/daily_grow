<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $product_id
 * @property int $branch_id
 * @property int|null $user_id
 * @property int $quantity
 * @property Carbon $receipt_date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @mixin Builder
 */
class ProductReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'branch_id',
        'user_id',
        'quantity',
        'receipt_date',
    ];

    protected $casts = [
        'receipt_date' => 'date',
    ];

    /**
     * Получить товар
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Получить филиал
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Получить пользователя
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
