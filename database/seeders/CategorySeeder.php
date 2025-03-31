<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Nieuws',
            'Technologie',
            'Lifestyle',
            'Business',
            'Sport',
        ];

        //Het zoekt naar een category met de naam $name.
        //Als die niet bestaat, wordt hij aangemaakt.
        //Als hij al bestaat, gebeurt er niets.
        foreach ($categories as $name) {
            Category::firstOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name), 'description' => '...']
            );
        }
    }
}
