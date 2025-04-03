<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();//auteur van de post
            $table->string('title_en');
            $table->string('title_nl');
            $table->string('title_fr');
            $table->string('title_es');
            $table->text('content_en');
            $table->text('content_nl');
            $table->text('content_fr');
            $table->text('content_es');
            $table->string('slug_en')->unique();
            $table->string('slug_nl')->unique();
            $table->string('slug_fr')->unique();
            $table->string('slug_es')->unique();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
