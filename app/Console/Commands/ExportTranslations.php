<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportTranslations extends Command
{
    protected $signature = 'translations:export';
    protected $description = 'Export PHP translations to JSON files in the root lang/ directory';

    public function handle()
    {
        $langPath = base_path('lang');

        // Check if the lang directory exists
        if (!File::exists($langPath)) {
            $this->error("The lang directory does not exist in the project root.");
            return;
        }

        // Get all language directories in the lang folder
        $directories = array_filter(File::directories($langPath), function ($dir) {
            return is_dir($dir);
        });

        foreach ($directories as $directory) {
            $lang = basename($directory);
            $translations = [];

            // Loop through all PHP translation files in the language directory
            foreach (File::allFiles($directory) as $file) {
                // Skip non-PHP files
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $filename = $file->getFilenameWithoutExtension();
                $translations[$filename] = require $file->getPathname();
            }

            // Define the JSON file path for the exported translations
            $jsonPath = "{$langPath}/{$lang}.json";

            // Write the translations to the JSON file
            File::put($jsonPath, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            $this->info("Exported translations to {$jsonPath}");
        }

        $this->info("Translation export completed!");
    }
}
