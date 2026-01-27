<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $branch_id
 * @property string $name
 * @property float $amount
 * @property Carbon $debt_date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @mixin Builder
 */
class Debt extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'name',
        'amount',
        'debt_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'debt_date' => 'date',
    ];

    /**
     * Получить филиал
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}

