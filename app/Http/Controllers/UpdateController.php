<?php
// app/Http/Controllers/UpdateController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class UpdateController extends Controller
{
    public function update(Request $request)
    {
        $steps = [
            'Checking repository' => 'git fetch',
            'Pulling latest code' => 'git pull origin main', // change branch
            'Installing dependencies' => 'composer install --no-dev --optimize-autoloader',
            'Running migrations' => 'php artisan migrate --force',
            'Clearing caches' => 'php artisan optimize:clear'
        ];

        $results = [];
        $success = true;

        foreach ($steps as $label => $cmd) {
            $process = Process::fromShellCommandline($cmd, base_path());
            $process->run();

            $results[] = [
                'step' => $label,
                'command' => $cmd,
                'output' => $process->getOutput() ?: $process->getErrorOutput(),
                'success' => $process->isSuccessful()
            ];

            if (!$process->isSuccessful()) {
                $success = false;
                break; // stop on failure
            }
        }

        return response()->json([
            'status' => $success ? 'success' : 'failed',
            'steps' => $results
        ]);
    }
}
