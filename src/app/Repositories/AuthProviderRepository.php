<?php

namespace App\Repositories;

use App\Models\AuthProvider;

class AuthProviderRepository
{

    /**
     * AuthProviderモデルのインスタンスを取得
     *
     * @return AuthProvider
     */
    public function model(): AuthProvider
    {
        return app(AuthProvider::class);
    }

}
