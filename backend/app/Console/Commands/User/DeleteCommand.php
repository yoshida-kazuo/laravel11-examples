<?php

namespace App\Console\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:delete
        {email : 削除対象メールアドレスをセットしてください}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ユーザを削除します';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $status = 0;
        $uuid = uuid();

        $this->info("{$uuid} start delete user {$this->argument('email')}");

        if (! $user = User::where('email', $this->argument('email'))
            ->first()
        ) {
            $this->error("{$uuid} user not found");

            return 91003;
        }

        if ($status === 0
            && $this->confirm('Do you wish to continue?')
        ) {
            if (! $user->delete()) {
                $this->error("{$uuid} failed to delete {$this->argument('email')}");

                $status = 91004;
            } else {
                $this->info("{$uuid} deleted {$this->argument('email')}");
            }
        }

        $this->info("{$uuid} end");

        return $status;
    }

}
