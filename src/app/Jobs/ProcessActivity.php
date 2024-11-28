<?php

namespace App\Jobs;

use App\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShoouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessActivity implements ShouldQueue
{

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 1200;

    /**
     * Mark the given job as failed if it should fail on timeouts.
     *
     * @var boolean
     */
    public $failOnTimeout = true;

    /**
     * message variable
     *
     * @var string
     */
    protected $message = null;

    /**
     * type variable
     *
     * @var string
     */
    protected $type = null;

    /**
     * Create a new job instance.
     *
     * @param string $message
     * @param string $type
     */
    public function __construct(
        string $message,
        string $type
    ) {
        $this->message = $message;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Activity::create([
            'user_id'   => user('id'),
            'type'      => $this->type,
            'message'   => $this->message,
        ]);
    }

}
