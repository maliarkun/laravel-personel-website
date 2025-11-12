<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(4);

        return [
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title.'-'.$this->faker->unique()->numberBetween(1, 9999)),
            'summary' => $this->faker->paragraph(),
            'featured_image' => null,
        ];
    }
}
