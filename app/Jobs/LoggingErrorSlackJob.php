<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LoggingErrorSlackJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $errorMessage;
    private $errorLog;
    public function __construct($errorMessage, $errorLog)
    {
        $this->errorMessage = $errorMessage;
        $this->errorLog = $errorLog;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::channel('slack')->error($this->errorMessage, $this->errorLog);
        } catch (\Exception $e) {
            dump($e->getMessage(), $e->getFile(), $e->getLine()). PHP_EOL;
        }
    }
}
