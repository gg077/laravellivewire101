<?php

namespace Database\Seeders;

use App\Models\Seo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // description
        Seo::firstOrCreate([
            'en' => 'A powerful and versatile Laravel application for efficient and modern web development.',
            'nl' => 'Een krachtige en veelzijdige Laravel-toepassing voor efficiënte en moderne webontwikkeling.',
            'fr' => 'Une application Laravel puissante et polyvalente pour un développement web efficace et moderne.',
            'es' => 'Una aplicación Laravel potente y versátil para un desarrollo web eficiente y moderno.',
        ]);

        // keywords
        Seo::firstOrCreate([
            'en' => 'Laravel, web application, PHP, MVC, routing, database, authentication, API, Laravel framework, backend, frontend, web development, RESTful, Eloquent ORM, Blade, security, session management, caching, queues',
            'nl' => 'Laravel, webtoepassing, PHP, MVC, routing, database, authenticatie, API, Laravel framework, backend, frontend, webontwikkeling, RESTful, Eloquent ORM, Blade, beveiliging, sessiebeheer, caching, wachtrijen',
            'fr' => 'Laravel, application web, PHP, MVC, routage, base de données, authentification, API, framework Laravel, backend, frontend, développement web, RESTful, Eloquent ORM, Blade, sécurité, gestion des sessions, mise en cache, files d\'attente',
            'es' => 'Laravel, aplicación web, PHP, MVC, enrutamiento, base de datos, autenticación, API, framework Laravel, backend, frontend, desarrollo web, RESTful, Eloquent ORM, Blade, seguridad, gestión de sesiones, caché, colas',
        ]);
    }
}
