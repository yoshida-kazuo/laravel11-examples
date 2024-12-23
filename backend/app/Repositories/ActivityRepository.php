<?php

namespace App\Repositories;

use App\Jobs\ProcessActivity;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ActivityRepository
{

    /**
     * ActivityRepository constructor
     *
     * @param string|null $message
     * @param string|null $type
     */
    public function __construct(
        string|null $message = null,
        string|null $type = null
    ) {
        if (isset($message)
            && isset($type)
        ) {
            $this->store($message, $type);
        }
    }

    /**
     * Store a new activity
     *
     * @param string $message
     * @param string $type
     * @return ActivityRepository
     */
    public function store(
        string $message,
        string $type
    ): ActivityRepository {
        $message = implode(' : ', [
                'v' . config('app.version'),
                $message,
            ]);

        if (! app()->runningInConsole()) {
            ProcessActivity::dispatch($message, $type)
                ->onConnection('sync')
                ->afterResponse();
        } else {
            ProcessActivity::dispatch($message, $type)
                ->onConnection('sync')
                ->afterCommit();
        }

        return $this;
    }

    /**
     * 'info'タイプの活動ログを保存
     *
     * @param string $message
     * @return ActivityRepository
     */
    public function info(string $message): ActivityRepository
    {
        return $this->store($message, 'info');
    }

    /**
     * 'create'タイプの活動ログを保存
     *
     * @param string $message
     * @return ActivityRepository
     */
    public function create(string $message): ActivityRepository
    {
        return $this->store($message, 'create');
    }

    /**
     * 'update'タイプの活動ログを保存
     *
     * @param string $message
     * @return ActivityRepository
     */
    public function update(string $message): ActivityRepository
    {
        return $this->store($message, 'update');
    }

    /**
     * 'delete'タイプの活動ログを保存
     *
     * @param string $message
     * @return ActivityRepository
     */
    public function delete(string $message): ActivityRepository
    {
        return $this->store($message, 'delete');
    }

    /**
     * 'login'タイプの活動ログを保存
     *
     * @param string $message
     * @return ActivityRepository
     */
    public function login(string $message): ActivityRepository
    {
        return $this->store($message, 'login');
    }

    /**
     * 'logout'タイプの活動ログを保存
     *
     * @param string $message
     * @return ActivityRepository
     */
    public function logout(string $message): ActivityRepository
    {
        return $this->store($message, 'logout');
    }

    /**
     * 'error'タイプの活動ログを保存
     *
     * @param string $message
     * @return ActivityRepository
     */
    public function error(string $message): ActivityRepository
    {
        return $this->store($message, 'error');
    }

    /**
     * 'warning'タイプの活動ログを保存
     *
     * @param string $message
     * @return ActivityRepository
     */
    public function warning(string $message): ActivityRepository
    {
        return $this->store($message, 'warning');
    }

    /**
     * 'dev'タイプの活動ログを保存 (本番環境以外でのみ保存)
     *
     * @param string $message
     * @return ActivityRepository
     */
    public function dev(string $message): ActivityRepository
    {
        if (! app()->isProduction()) {
            return $this->store($message, 'dev');
        }

        return $this;
    }

    /**
     * 活動ログのカタログを取得
     *
     * @param array $conditions
     * @param int $perPage
     * @param string $catalogName
     * @param int $onEachSide
     * @return LengthAwarePaginator
     */
    public function catalog(
        array $conditions,
        int $perPage = 15,
        string $catalogName = 'activity-catalog',
        int $onEachSide = 1
    ): LengthAwarePaginator {
        $conditions = collect($conditions);
        $activities = Activity::with('user');

        $activities->where(function (Builder $query) {
            $users = User::select('id')
                ->whereColumn('user.id', 'activities.user_id')
                ->where('role_id', '>=', user('role_id'));

            $query->whereNull('user_id')
                ->orWhereExists($users);
        });

        if ($conditions->get('type')) {
            $activities->where('type', $conditions->get('type'));
        }

        if ($conditions->get('message')) {
            $activities->where('message', 'like', '%' . $conditions->get('message') . '%');
        }

        if (! $conditions->get('order')) {
            $conditions->put('order', [
                'id' => 'desc',
            ]);
        }

        foreach ($conditions->get('order') as $column => $direction) {
            $activities->orderBy($column, $direction);
        }

        return $activities->paginate(
                $perPage,
                ['*'],
                $catalogName
            )
            ->onEachSide($onEachSide);
    }

}
