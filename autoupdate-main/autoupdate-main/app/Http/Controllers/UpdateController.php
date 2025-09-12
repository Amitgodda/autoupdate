<?php
// app/Http/Controllers/UpdateController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;

class UpdateController extends Controller
{
 public function update()
{
    // run the artisan command and capture output
    Artisan::call('app:update');

    $output = Artisan::output();

    return response()->json([
        'status' => str_contains($output, 'complete') ? 'success' : 'failed',
        'output' => $output
    ]);
}
}
