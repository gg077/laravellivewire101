<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TranslationsImport;

class ImportTranslations extends Command
{
    // Command om de vertalingen te importeren
    protected $signature = 'translations:import {file}';
    protected $description = 'Importeer vertalingen vanuit een Excel-bestand en sla ze op als JSON';

    public function handle()
    {
        $filePath = $this->argument('file');

        // Controleer of het bestand bestaat
        if (!File::exists($filePath)) {
            $this->error("Bestand niet gevonden: {$filePath}");
            return;
        }

        // Voer de import uit met Laravel Excel
        Excel::import(new TranslationsImport, $filePath);

        $this->info("Vertalingen succesvol geïmporteerd uit {$filePath}");
    }
}
