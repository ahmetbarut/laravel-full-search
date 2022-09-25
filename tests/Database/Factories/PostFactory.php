<?php

namespace AhmetBarut\FullSearch\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = \AhmetBarut\FullSearch\Tests\Models\Post::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'summary' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'slug' => $this->faker->slug(),
        ];
    }
}
