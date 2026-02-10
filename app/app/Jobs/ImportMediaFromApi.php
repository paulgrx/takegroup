<?php

namespace App\Jobs;

use App\Services\TmdbImportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ImportMediaFromApi implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->onQueue('dispatch-import-data');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = app(TmdbImportService::class);

        $service->importGenres();
        $service->importMovies();
        $service->importSeries();

        Log::info('Import job completed');
    }
}
