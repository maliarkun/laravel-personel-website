<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Note;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'project_id' => null,
            'title' => $this->faker->sentence(6),
            'content' => collect(range(1, 3))->map(fn () => '<p>'.$this->faker->paragraph().'</p>')->join(''),
        ];
    }

    public function forProject(Project $project): static
    {
        return $this->state(fn () => [
            'category_id' => $project->category_id,
            'project_id' => $project->id,
        ]);
    }
}
