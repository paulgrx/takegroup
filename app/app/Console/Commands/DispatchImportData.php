<?php

namespace App\Console\Commands;

use App\Jobs\ImportMediaFromApi;
use Illuminate\Console\Command;

class DispatchImportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dispatch-import-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch job to import data from TMDB';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        ImportMediaFromApi::dispatch()->onQueue('dispatch-import-data');
    }
}
