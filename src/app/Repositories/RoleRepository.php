<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository
{

    /**
     * 全てのロールを取得
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return Role::all();
    }

}
