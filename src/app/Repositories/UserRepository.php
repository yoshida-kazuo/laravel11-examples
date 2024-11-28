<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository
{

    /**
     * Userインスタンスを返す
     *
     * @return User
     */
    public function model(): User
    {
        return app(User::class);
    }

    /**
     * 指定したIDのユーザを返す
     *
     * @param integer $id
     * @param boolean $withTrashed
     * @return Collection|null
     */
    public function find(
        int $id,
        bool $withTrashed = false
    ): mixed {
        $user = $this->model()
            ->with('role');

        if ($withTrashed) {
            $user->withTrashed();
        }

        return $user->find($id);
    }

    /**
     * ユーザを作成してUserインスタンスを返す
     *
     * @param array $values
     * @param boolean $emailVerified
     * @return User
     */
    public function store(
        array $values,
        bool $emailVerified = false
    ): User {
        $user = $this->model();

        foreach ($values as $column => $value) {
            $user->{$column} = $value;
        }

        if ($emailVerified === true) {
            $user->email_verified_at = now();
        }

        $user->save();

        activity()
            ->info(__(':userId : :email : :name : Create user information. : :postData : :userData', [
                'userId'        => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'postData'      => print_r($values, true),
                'userData'      => print_r($user->toArray(), true),
            ]));

        return $user;
    }

    /**
     * ユーザ情報を更新する
     *
     * @param integer $id
     * @param array $values
     * @param boolean|null|null $isLoginProhibited
     * @param boolean $isRestore
     * @param boolean $withTrashed
     * @return boolean
     */
    public function update(
        int $id,
        array $values,
        bool|null $isLoginProhibited = null,
        bool $isRestore = false,
        bool $withTrashed = false
    ): bool {
        $user = $this->find($id, $withTrashed);

        foreach ($values as $column => $value) {
            $user->{$column} = $value;
        }

        activity()
            ->info(__(':userId : :email : Update user information. : :currentData : :newData', [
                'userId'        => $user->id,
                'email'         => $user->email,
                'currentData'   => print_r($user->toArray(), true),
                'newData'       => print_r($values, true),
            ]));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;

            activity()
                ->info(__(':userId : :email : :oldEmail : The email address has been changed.', [
                    'userId'    => $user->id,
                    'email'     => $user->email,
                    'oldEmail'  => data_get($values, 'email'),
                ]));
        }

        if (is_bool($isLoginProhibited)) {
            if (! $isLoginProhibited && $user->login_ban_at) {
                $user->login_ban_at = null;
            } elseif ($isLoginProhibited && is_null($user->login_ban_at)) {
                $user->login_ban_at = now();
            }
        }

        if ($user->isDirty('login_ban_at')) {
            activity()
                ->info(__(':userId : :email : The login restriction status has been changed. : :status', [
                    'userId'    => $user->id,
                    'email'     => $user->email,
                    'status'    => $user->login_ban_at ? 'Login prohibited' : 'Login permitted',
                ]));
        }

        if ($isRestore) {
            activity()
                ->info(__(':userId : :email : The deletion flag has been removed.', [
                    'userId'    => $user->id,
                    'email'     => $user->email,
                ]));
        }

        return $isRestore ? $user->restore() : $user->save();
    }

    /**
     * ユーザ情報を削除する
     *
     * @param integer $id
     * @param callable|null|null $callbackFunction
     * @return boolean|null
     */
    public function delete(
        int $id,
        callable|null $callbackFunction = null
    ): bool|null {
        $user = $this->find($id);
        $delete = $user->delete();

        if ($callbackFunction) {
            call_user_func($callbackFunction, $user);
        }

        if ($delete) {
            activity()
                ->info(__(':userId : :email : The account has been deleted.', [
                    'userId'    => $user->id,
                    'email'     => $user->email,
                ]));
        }

        return $delete;
    }

    public function catalog(
        array $conditions = [],
        int $perPage = 15,
        string $catalogName = 'user-catalog',
        int $onEachSide = 1,
        bool $aboveCurrentAuth = false,
        array $excludeUserIds = [],
        array $excludeRoleIds = [],
        bool $withTrashed = false
    ): LengthAwarePaginator {
        $conditions = collect($conditions);
        $users = $this->model()
            ->with('role');

        if (! empty($excludeUserIds)) {
            $users->whereNotIn('id', $excludeUserIds);
        }

        if ($aboveCurrentAuth === true) {
            $users->where('role_id', '>=', user('role_id'));
        }

        if (! empty($excludeRoleIds)) {
            $users->whereNotIn('role_id', $excludeRoleIds);
        }

        if ($withTrashed) {
            $users->withTrashed();
        }

        if ($conditions->get('role_id')) {
            $users->where('role_id', $conditions->get('role_id'));
        }

        if ($conditions->get('name')) {
            $users->where('name', 'like', "%{$conditions->get('name')}%");
        }

        if ($conditions->get('email')) {
            $users->where('email', 'like', "{$conditions->get('email')}%");
        }

        if (! $conditions->get('order')) {
            $conditions->put('order', [
                'id' => 'desc',
            ]);
        }

        foreach ($conditions->get('order') as $col => $order) {
            $users->orderBy($col, $order);
        }

        return $users->paginate(
                $perPage,
                ['*'],
                $catalogName
            )
            ->onEachSide($onEachSide);
    }

    public function createUniqueColumn(
        string $uniqueColumn = 'email',
        string|null $prefix = null,
        string|null $suffix = null,
        int $length = 16,
        int $maxAttempts = 12
    ): string {
        $isUnique = false;

        for ($attempts = 0; $attempts < $maxAttempts; $attempts++) {
            $randomString = Str::random($length);

            if ($prefix) {
                $randomString = "{$prefix}{$randomString}";
            }

            if ($suffix) {
                $randomString .= $suffix;
            }

            if (! $this->model()->where($uniqueColumn, $randomString)->exists()) {
                $isUnique = true;

                break;
            }
        }

        if (! $isUnique) {
            throw new \App\Exceptions\AppErrorException('Failed to generate unique data.');
        }

        return $randomString;
    }

}
