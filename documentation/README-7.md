# Laravel Translation Importer

This package provides an Artisan command to import translations from Excel files into your Laravel application, making it easy to manage multilingual content.

## Overview

The `translations:import` command imports translations from an Excel spreadsheet and saves them as PHP files in your Laravel application's `lang/` directory. This streamlines the translation management process for multilingual applications.

## Installation

### Required Package

First, install Laravel Excel:

```bash
composer require maatwebsite/laravel-excel
```

### Command Setup

Copy the code below and create a new file named `ImportTranslations.php` in your Laravel application's `app/Console/Commands` directory:

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TranslationsImport;

class ImportTranslations extends Command
{
    /**
     * The name and signature of the console command.
     * Includes a required parameter for the file path.
     *
     * @var string
     */
    protected $signature = 'translations:import {file}';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importeer vertalingen vanuit een Excel-bestand en sla ze op als JSON';

    /**
     * Execute the console command.
     * Handles the Excel import process.
     *
     * @return void
     */
    public function handle()
    {
        // Get the file path from the command argument
        $filePath = $this->argument('file');

        // Check if the file exists
        if (!File::exists($filePath)) {
            $this->error("Bestand niet gevonden: {$filePath}");
            return;
        }

        // Import translations using Laravel Excel
        Excel::import(new TranslationsImport, $filePath);

        // Display success message
        $this->info("Vertalingen succesvol geïmporteerd uit {$filePath}");
    }
}
```

### Create the TranslationsImport Class

Create a new file named `TranslationsImport.php` in your Laravel application's `app/Imports` directory:

```php
<?php

namespace App\Imports;

use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\ToArray;

class TranslationsImport implements ToArray
{
    /**
     * Process the imported array data.
     * Expects columns: language code, translation key, translated text
     * 
     * @param array $rows
     * @return void
     */
    public function array(array $rows)
    {
        foreach ($rows as $row) {
            if (isset($row[0], $row[1], $row[2])) {
                $lang = trim($row[0]); // Language code (e.g., "nl", "fr", "en")
                $key = trim($row[1]);  // Translation key (e.g., "welcome_message")
                $value = trim($row[2]); // Translated text (e.g., "Welkom!")

                // Path to the PHP file in the lang/{lang}/ directory
                $langDirectory = base_path("lang/{$lang}");
                $phpPath = "{$langDirectory}/imports.php";

                // Create the directory if it doesn't exist
                if (!File::exists($langDirectory)) {
                    File::makeDirectory($langDirectory, 0755, true);
                }

                // Check if the file already exists and load existing translations
                $translations = File::exists($phpPath)
                    ? require $phpPath
                    : [];

                // Add the new translation
                $translations[$key] = $value;

                // Generate PHP code for the translations
                $phpContent = "<?php\n\nreturn " . var_export($translations, true) . ";\n";

                // Save the updated translations as PHP
                File::put($phpPath, $phpContent);
            }
        }
    }
}
```

## Usage

Run the command from your terminal, providing the path to your Excel file:

```bash
php artisan translations:import /path/to/your/translations.xlsx
```

## Excel File Format

Your Excel file should have the following column structure:

| Column 1 | Column 2 | Column 3 |
|---------|---------|---------|
| en | welcome | Welcome to our application |
| en | goodbye | Goodbye |
| nl | welcome | Welkom bij onze applicatie |
| nl | goodbye | Tot ziens |
| fr | welcome | Bienvenue dans notre application |
| fr | goodbye | Au revoir |

- Column 1: The language code (e.g., "en", "nl", "fr")
- Column 2: The translation key
- Column 3: The translated text

The importer processes each row individually, so you can mix languages throughout the file.

## How It Works

The import command:

1. Reads the provided Excel file
2. Processes each row as a translation entry
3. Groups translations by language code
4. Creates or updates PHP translation files for each language in the `lang/{$lang}/` directories

For example, if your Excel contains translations for English (en), Dutch (nl), and French (fr), the command will create:
- `lang/en/imports.php`
- `lang/nl/imports.php`
- `lang/fr/imports.php`

Each file will contain all translation keys for that language in a PHP array format:

```php
<?php

return array(
  'welcome' => 'Welcome to our application',
  'goodbye' => 'Goodbye',
  'login' => 'Login',
);
```

## Benefits

- **Streamlined Workflow**: Update translations in Excel and import with a single command
- **Team Collaboration**: Share Excel files with translators who don't need access to code
- **Batch Updates**: Manage thousands of translations simultaneously
- **Laravel Integration**: PHP files integrate seamlessly with Laravel's translation system

## Requirements

- Laravel 12
- PHP 8.1 or higher
- Maatwebsite/Laravel-Excel 3.1 or higher

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
