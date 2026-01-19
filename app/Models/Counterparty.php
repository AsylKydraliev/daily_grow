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
 * @property int|null $branch_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @mixin Builder
 */
class Counterparty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'branch_id',
    ];

    /**
     * Получить продажи для контрагента
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Получить филиал контрагента
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
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
