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
 * @property int $counterparty_id
 * @property int $branch_id
 * @property int|null $user_id
 * @property int $quantity
 * @property float $price
 * @property Carbon $sale_date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @mixin Builder
 */
class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'counterparty_id',
        'branch_id',
        'user_id',
        'quantity',
        'price',
        'sale_date',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_date' => 'date',
    ];

    /**
     * Получить товар
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Получить контрагента
     */
    public function counterparty(): BelongsTo
    {
        return $this->belongsTo(Counterparty::class);
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
