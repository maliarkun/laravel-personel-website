<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GitHubProjectService
{
    public function fetchRepositories(): void
    {
        $username = config('services.github.username', 'maliarkun');
        $token = config('services.github.token');

        $response = Http::withHeaders($this->headers($token))
            ->get("https://api.github.com/users/{$username}/repos", [
                'per_page' => 100,
                'sort' => 'pushed',
                'direction' => 'desc',
            ]);

        $response->throw();

        $categoryId = $this->resolveCategoryId();

        foreach ($response->json() as $repository) {
            $slug = Str::slug($repository['name']);
            $description = $repository['description'] ?? null;
            $topics = $repository['topics'] ?? [];

            Project::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $repository['name'],
                    'title' => $repository['name'],
                    'description' => $description,
                    'summary' => $description ?? 'Imported from GitHub',
                    'repo_name' => $repository['name'],
                    'repo_url' => $repository['html_url'],
                    'github_url' => $repository['html_url'],
                    'stars' => $repository['stargazers_count'] ?? 0,
                    'topics' => $topics,
                    'language' => $repository['language'],
                    'pushed_at' => $repository['pushed_at'] ?? null,
                    'created_at' => $repository['created_at'] ?? now(),
                    'category_id' => $categoryId,
                ]
            );
        }
    }

    protected function headers(?string $token): array
    {
        $headers = [
            'Accept' => 'application/vnd.github+json',
            'X-GitHub-Api-Version' => '2022-11-28',
        ];

        if ($token) {
            $headers['Authorization'] = 'Bearer '.$token;
        }

        return $headers;
    }

    protected function resolveCategoryId(): int
    {
        $category = Category::firstOrCreate(
            ['slug' => 'github-projects'],
            [
                'name' => 'GitHub Projects',
                'description' => 'Repositories imported automatically from GitHub.',
            ]
        );

        return $category->id;
    }
}
