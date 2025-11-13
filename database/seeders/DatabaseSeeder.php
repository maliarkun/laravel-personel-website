<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Note;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(MembershipSeeder::class); // Gelişmiş üyelik verilerini oluştur.

        Category::factory()
            ->count(5)
            ->has(Project::factory()->count(2))
            ->create()
            ->each(function (Category $category) {
                Note::factory()->count(3)->create([
                    'category_id' => $category->id,
                ]);

                $category->projects->each(function (Project $project) {
                    Note::factory()->forProject($project)->create();
                });
            });
    }
}
