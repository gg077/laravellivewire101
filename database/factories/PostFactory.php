<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $title_en = '(en) '. $this->faker->sentence();
        $title_nl = '(nl) '. $this->faker->sentence();
        $title_fr = '(fr) '. $this->faker->sentence();
        $title_es = '(es) '. $this->faker->sentence();
        return [
            //
            'author_id'=>User::inRandomOrder()->first()->id ?? User::factory(),
            'title_en'=>$title_en,
            'title_nl'=>$title_nl,
            'title_fr'=>$title_fr,
            'title_es'=>$title_es,
            'content_en'=>'(en) '.$this->faker->paragraphs(3,true),
            'content_nl'=>'(nl) '.$this->faker->paragraphs(3,true),
            'content_fr'=>'(fr) '.$this->faker->paragraphs(3,true),
            'content_es'=>'(es) '.$this->faker->paragraphs(3,true),
            'slug_en'=>Str::slug($title_en), //koppeltekens tussen de woorden
            'slug_nl'=>Str::slug($title_nl), //koppeltekens tussen de woorden
            'slug_fr'=>Str::slug($title_fr), //koppeltekens tussen de woorden
            'slug_es'=>Str::slug($title_es), //koppeltekens tussen de woorden
            'is_published'=>1,
        ];
    }
}
