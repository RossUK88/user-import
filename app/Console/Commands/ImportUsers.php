<?php

namespace App\Console\Commands;

use App\Jobs\FetchUserImportCSV;
use App\Jobs\ImportUsers as ImportUsersJob;
use Illuminate\Bus\Batch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;

class ImportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches a Batch of Jobs to fetch the User CSV and start importing the Users into the Table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Throwable
     */
    public function handle()
    {
        $this->info("Dispatching Bus");

        Bus::batch([
            new FetchUserImportCSV(),
            new ImportUsersJob()
            // Delete CSV
        ])->then(function (Batch $batch) {
            Log::info("Finished Batch");
            Log::info("Fails {$batch->failedJobs}");
        })->catch(function (Batch $batch, Throwable $e) {
            Log::error("Errors bro");
            Log::error($e->getMessage());
            Log::info("Fails {$batch->failedJobs}");
        })->finally(function (Batch $batch) {
            Log::info("User Import Finished");
            Log::info($batch->id);
        })->name("User Import")
            ->allowFailures()
            ->dispatch();

        $this->info("Finished");
    }
}
