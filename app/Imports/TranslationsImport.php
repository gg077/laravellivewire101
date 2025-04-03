<?php

namespace App\Imports;

use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\ToArray;

class TranslationsImport implements ToArray
{
    public function array(array $rows)
    {
        foreach ($rows as $row) {
            if (isset($row[0], $row[1], $row[2])) {
                $lang = trim($row[0]); // Taalcode (bijv. "nl", "fr", "en")
                $key = trim($row[1]);  // Vertaal sleutel (bijv. "welcome_message")
                $value = trim($row[2]); // Vertaalde tekst (bijv. "Welkom!")

                // Pad naar het juiste JSON-bestand in de root lang/ map
                $jsonPath = base_path("lang/{$lang}/{$lang}.json");

                // Controleer of het bestand al bestaat en laad de bestaande vertalingen
                $translations = File::exists($jsonPath)
                    ? json_decode(File::get($jsonPath), true)
                    : [];

                // Voeg de nieuwe vertaling toe
                $translations[$key] = $value;

                // Sla de bijgewerkte vertalingen op als JSON
                File::put($jsonPath, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
        }
    }
}
