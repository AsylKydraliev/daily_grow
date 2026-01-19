<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $branch_id
 * @property string $name
 * @property float $price
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @mixin Builder
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'name',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Получить филиал
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Получить приходы товара
     */
    public function productReceipts(): HasMany
    {
        return $this->hasMany(ProductReceipt::class);
    }

    /**
     * Получить продажи товара
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Фильтрация записей
     */
    public function scopeFilter(Builder $query, array $data = []): void
    {
        if (isset($data['search'])) {
            if (env('DB_CONNECTION') === 'pgsql') {
                $query->where('name', 'ilike', '%'.$data['search'].'%');
            } else {
                $query->where('name', 'like', '%'.$data['search'].'%');
            }
        }
    }
}
