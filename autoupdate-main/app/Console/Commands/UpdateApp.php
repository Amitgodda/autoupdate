<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use ZipArchive;

class UpdateApp extends Command
{
    protected $signature = 'app:update';
    protected $description = 'Download and apply the latest release from GitHub';

    public function handle()
    {
        $this->info('Downloading latest release…');

        // Example: get the latest release ZIP from GitHub
        $url = 'https://github.com/Amitgodda/autoupdate/archive/refs/heads/main.zip';
        $zipFile = storage_path('app/update.zip');

        file_put_contents($zipFile, file_get_contents($url));

        $this->info('Extracting…');

        $zip = new ZipArchive;
        if ($zip->open($zipFile) === true) {
            $zip->extractTo(base_path()); // overwrite files
            $zip->close();
        } else {
            $this->error('Could not open ZIP');
            return 1;
        }

        $this->info('Installing dependencies…');
        shell_exec('composer install --no-dev --optimize-autoloader');

        $this->info('Running migrations…');
        \Artisan::call('migrate', ['--force' => true]);

        $this->info('Clearing cache…');
        \Artisan::call('optimize:clear');

        $this->info('Update complete.');
        return 0;
    }
}
