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

                // Pad naar het juiste PHP-bestand in de lang/{lang}/ map
                $langDirectory = base_path("lang/{$lang}");
                $phpPath = "{$langDirectory}/imports.php";

                // Maak de directory aan als deze niet bestaat
                if (!File::exists($langDirectory)) {
                    File::makeDirectory($langDirectory, 0755, true);
                }

                // Controleer of het bestand al bestaat en laad de bestaande vertalingen
                $translations = File::exists($phpPath)
                    ? require $phpPath
                    : [];

                // Voeg de nieuwe vertaling toe
                $translations[$key] = $value;

                // Genereer PHP code voor de vertalingen
                $phpContent = "<?php\n\nreturn " . var_export($translations, true) . ";\n";

                // Sla de bijgewerkte vertalingen op als PHP
                File::put($phpPath, $phpContent);
            }
        }
    }
}
