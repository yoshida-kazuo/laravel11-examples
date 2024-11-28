<?php

namespace App\Models;

use App\Models\Trait\Timezone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Testing\Fluent\Concerns\Has;

class Provider extends Model
{

    use HasFactory,
        Timezone;

    /**
     * The attribute that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * auth_providersテーブルとのリレーションを定義
     *
     * @return HasMany
     */
    public function authProviders(): HasMany
    {
        return $this->hasMany(AuthProvider::class);
    }

}
