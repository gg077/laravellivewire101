# Laravel Translation Exporter

This package provides an Artisan command to export your Laravel PHP translation files to JSON format, making them easily accessible for frontend usage.

## Overview

The `translations:export` command converts all PHP translation files in your Laravel application's `lang` directory to JSON files. This facilitates the integration of your translations with JavaScript frameworks and other tools.

## Installation

Copy the code below and create a new file named `ExportTranslations.php` in your Laravel application's `app/Console/Commands` directory.

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportTranslations extends Command
{
    /**
     * The name and signature of the console command.
     * This defines how the command will be called from the terminal.
     *
     * @var string
     */
    protected $signature = 'translations:export';
    
    /**
     * The console command description.
     * Provides a brief explanation of what the command does.
     *
     * @var string
     */
    protected $description = 'Export PHP translations to JSON files in the root lang/ directory';

    /**
     * Execute the console command.
     * This method contains the main logic of the command.
     *
     * @return void
     */
    public function handle()
    {
        // Get the base path to the lang directory
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

        // Process each language directory
        foreach ($directories as $directory) {
            // Extract the language code from the directory name
            $lang = basename($directory);
            
            // Initialize an array to store all translations for this language
            $translations = [];

            // Loop through all PHP translation files in the language directory
            foreach (File::allFiles($directory) as $file) {
                // Skip non-PHP files
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                // Get the filename without extension (will be used as the translation group key)
                $filename = $file->getFilenameWithoutExtension();
                
                // Load the PHP translation file and store its contents
                $translations[$filename] = require $file->getPathname();
            }

            // Define the JSON file path for the exported translations
            $jsonPath = "{$langPath}/{$lang}.json";

            // Write the translations to the JSON file with pretty formatting
            File::put($jsonPath, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            // Display success message for this language
            $this->info("Exported translations to {$jsonPath}");
        }

        // Display final success message
        $this->info("Translation export completed!");
    }
}
```

Laravel 12 will automatically discover and register the command.

## Usage

Run the command from your terminal:

```bash
php artisan translations:export
```

## How It Works

The command performs the following actions:

1. Scans the `lang` directory in your project root
2. Identifies all language subdirectories (e.g., `en`, `nl`, `fr`)
3. For each language:
    - Reads all PHP translation files
    - Combines translations into a single JSON structure
    - Exports a JSON file to `lang/{language}.json`

## Example

If your project structure looks like:

```
/lang
  /en
    messages.php
    validation.php
  /nl
    messages.php
    validation.php
```

After running the command, you'll get:

```
/lang
  en.json  <-- New file with combined translations
  nl.json  <-- New file with combined translations
  /en
    messages.php
    validation.php
  /nl
    messages.php
    validation.php
```

The JSON files will contain all translations in a structured format:

```json
{
  "messages": {
    "welcome": "Welcome to our application",
    "goodbye": "Goodbye"
  },
  "validation": {
    "required": "This field is required",
    "email": "Please enter a valid email address"
  }
}
```

## Benefits

- **Frontend Integration**: Easily use translations in JavaScript applications
- **Automation**: Simplify your translation workflow
- **Consistency**: Ensure frontend and backend translations remain in sync
- **Tool Integration**: Compatible with translation management systems

## Requirements

- Laravel 12
- PHP 8.1 or higher

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
