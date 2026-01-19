<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'login',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Проверить, является ли пользователь супер-админом
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'супер-админ';
    }

    /**
     * Проверить, является ли пользователь админом или супер-админом
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['админ', 'супер-админ']);
    }

    /**
     * Проверить, может ли пользователь управлять пользователями
     */
    public function canManageUsers(): bool
    {
        return $this->isSuperAdmin();
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'login';
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
