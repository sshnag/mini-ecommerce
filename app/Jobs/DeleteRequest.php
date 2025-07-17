<?php

namespace App\Jobs;

use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeleteRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

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
        $thresholdDate = Carbon::now()->subMonths(4);

        // Delete soft-deleted contacts older than 4 months
        $deleted = Contact::onlyTrashed()
            ->where('deleted_at', '<', $thresholdDate)
            ->forceDelete();

        // Log the result
        Log::info("Deleted {$deleted} old contact requests.");
    }
}
