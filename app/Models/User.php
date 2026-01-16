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
            $search = trim($data['search']);
            if (empty($search)) {
                return;
            }

            // Разбиваем поисковый запрос на слова
            $words = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);
            
            $dbConnection = env('DB_CONNECTION', 'mysql');
            
            // Для каждого слова добавляем условие поиска
            $query->where(function ($q) use ($words, $dbConnection) {
                foreach ($words as $word) {
                    // Экранируем специальные символы LIKE
                    $wordEscaped = str_replace(['%', '_'], ['\%', '\_'], $word);
                    
                    if ($dbConnection === 'pgsql') {
                        // PostgreSQL поддерживает ilike для case-insensitive поиска
                        $q->where(function ($subQ) use ($wordEscaped) {
                            $subQ->where('name', 'ilike', '%' . $wordEscaped . '%')
                                ->orWhere('login', 'ilike', '%' . $wordEscaped . '%');
                        });
                    } elseif ($dbConnection === 'sqlite') {
                        // SQLite: LOWER() и COLLATE NOCASE не работают с кириллицей
                        // Генерируем все возможные варианты регистра для поиска
                        $variants = $this->generateCaseVariants($wordEscaped);
                        $q->where(function ($subQ) use ($variants) {
                            $subQ->where(function ($nameQ) use ($variants) {
                                foreach ($variants as $variant) {
                                    $nameQ->orWhere('name', 'like', '%' . $variant . '%');
                                }
                            })->orWhere(function ($loginQ) use ($variants) {
                                foreach ($variants as $variant) {
                                    $loginQ->orWhere('login', 'like', '%' . $variant . '%');
                                }
                            });
                        });
                    } else {
                        // MySQL и другие: используем like с collation utf8mb4_unicode_ci (case-insensitive)
                        $q->where(function ($subQ) use ($wordEscaped) {
                            $subQ->where('name', 'like', '%' . $wordEscaped . '%')
                                ->orWhere('login', 'like', '%' . $wordEscaped . '%');
                        });
                    }
                }
            });
        }
    }

    /**
     * Генерирует варианты регистра для поиска в SQLite
     */
    private function generateCaseVariants(string $word): array
    {
        $variants = [];
        $wordLower = mb_strtolower($word, 'UTF-8');
        $wordUpper = mb_strtoupper($word, 'UTF-8');
        $wordTitle = mb_convert_case($word, MB_CASE_TITLE, 'UTF-8');
        
        // Базовые варианты
        $variants[] = $word;
        $variants[] = $wordLower;
        $variants[] = $wordUpper;
        $variants[] = $wordTitle;
        
        $wordLength = mb_strlen($word, 'UTF-8');
        
        // Для коротких слов (до 5 символов) генерируем дополнительные варианты
        if ($wordLength <= 5 && $wordLength > 1) {
            // Вариант с первой заглавной и остальными маленькими
            $firstUpper = mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8') . 
                          mb_strtolower(mb_substr($word, 1, null, 'UTF-8'), 'UTF-8');
            $variants[] = $firstUpper;
            
            // Вариант с первой маленькой и остальными заглавными
            $firstLower = mb_strtolower(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8') . 
                          mb_strtoupper(mb_substr($word, 1, null, 'UTF-8'), 'UTF-8');
            $variants[] = $firstLower;
            
            // Для очень коротких слов (до 4 символов) добавляем варианты с последней заглавной
            if ($wordLength <= 4) {
                // Первая заглавная, последняя заглавная, остальные маленькие
                $firstLastUpper = mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8') . 
                                  mb_strtolower(mb_substr($word, 1, $wordLength - 2, 'UTF-8'), 'UTF-8') . 
                                  mb_strtoupper(mb_substr($word, -1, 1, 'UTF-8'), 'UTF-8');
                $variants[] = $firstLastUpper;
            }
        }
        
        return array_unique($variants);
    }
}
