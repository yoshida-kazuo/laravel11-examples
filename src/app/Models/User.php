<?php

namespace App\Models;

use App\Models\Trait\Timezone;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        SoftDeletes,
        Timezone;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'email_verified_at',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * ユーザーのロール情報を格納
     *
     * @var array|null
     */
    protected $roles = null;

    /**
     * ユーザーのロール情報を取得
     *
     * @return array
     */
    public function roles(): array
    {
        if ($this->roles !== null) {
            return $this->roles;
        }

        $this->roles = $this->all()
            ->pluck('role', 'id')
            ->toArray();

        return $this->roles;
    }

    /**
     * ログインユーザーのロール情報からリダイレクト先ダッシュボードを取得
     *
     * @return string
     */
    public function dashboardRoute(): string
    {
        return $this->role->redirect_route;
    }

    /**
     * ユーザのロール情報から権限を判定
     *
     * @param string $role
     * @param boolean $strict
     * @return boolean
     */
    public function isAuthorizeUser(
        string $role,
        bool $strict = false
    ): bool {
        $isAuthorizeUser = false;
        $roleId = data_get(array_flip($this->roles()), $role);

        if ($strict === true) {
            $isAuthorizeUser = $this->role_id === $roleId;
        } else {
            $isAuthorizeUser = $this->role_id <= $roleId;
        }

        return $isAuthorizeUser;
    }

    /**
     * rolesテーブルとのリレーションを定義
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * auth_providersテーブルとのリレーションを定義
     *
     * @return HasMany
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

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
