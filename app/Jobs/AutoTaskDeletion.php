<?php

namespace App\Jobs;

use App\Models\Task;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoTaskDeletion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $paramsDays = now()->subDays(30)->toDateString();

        Task::whereNotNull('trash_at')->whereNull('deleted_at')->whereDate('trash_at', $paramsDays)->update(['deleted_at' => now()]);
    }
}
