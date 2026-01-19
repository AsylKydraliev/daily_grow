<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $address
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @mixin Builder
 */
class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
    ];

    /**
     * Получить приходы товаров для филиала
     */
    public function productReceipts(): HasMany
    {
        return $this->hasMany(ProductReceipt::class);
    }

    /**
     * Получить продажи для филиала
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Получить контрагентов филиала
     */
    public function counterparties(): HasMany
    {
        return $this->hasMany(Counterparty::class);
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
