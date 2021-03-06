
<?php

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/batch/{batchId}', function (string $batchId) {
    $batch = Bus::findBatch($batchId);

    if(count($batch->failedJobIds) > 0) {
        $failedJobs = DB::table('failed_jobs')->whereIn('uuid', $batch->failedJobIds)->get();
    }

    return view('batch', [
        'batchId' => $batchId,
        'batch' => $batch,
        'failedJobs' => $failedJobs ?? []
    ]);
});
