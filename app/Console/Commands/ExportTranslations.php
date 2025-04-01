<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportTranslations extends Command
{
    // Define the Artisan command signature and description
    protected $signature = 'translations:export';
    protected $description = 'Export PHP translations to JSON files in the root lang/ directory';

    public function handle()
    {
        // List of languages to export translations for
        $languages = ['nl', 'fr', 'en']; // Add more languages if needed

        foreach ($languages as $lang) {
            // Define the path where PHP translation files are stored (lang/{lang}/)
            $path = base_path("lang/{$lang}");
            $translations = [];

            // Check if the language directory exists
            if (File::exists($path)) {
                // Loop through all PHP translation files in the directory
                foreach (File::allFiles($path) as $file) {
                    $filename = pathinfo($file, PATHINFO_FILENAME);

                    // Load translation data from the PHP file and store it in an array
                    $translations[$filename] = require $file;
                }
            }

            // Ensure the root lang/ directory exists, create it if necessary
            $langDir = base_path("lang");
            if (!File::exists($langDir)) {
                File::makeDirectory($langDir, 0755, true);
            }

            // Define the JSON file path for the exported translations
            $jsonPath = base_path("lang/{$lang}.json");

            // Write the translations to the JSON file in a readable format
            File::put($jsonPath, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            // Output a success message in the console
            $this->info("Exported translations to {$jsonPath}");
        }
    }
}
