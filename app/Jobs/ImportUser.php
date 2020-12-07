<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ImportUser implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable;

    /**
     * @param  User  $user
     */
    public function __construct(public User $user) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(rand(1, 2) === 1) {
            throw new \Exception("Ash is a Turkey Twizzler");
        }

        Log::info($this->user->name);
        // Handle if this is an update or an insert
        $this->user->save();
    }
}
