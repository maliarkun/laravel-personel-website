<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FetchGithubRepos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-github-repos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch public repositories from GitHub and sync them to the projects table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = 'maliarkun';
        $url = "https://api.github.com/users/{$username}/repos?sort=updated&per_page=100";

        $this->info("Fetching repositories for user: {$username}...");

        $response = Http::get($url);

        if ($response->failed()) {
            $this->error('Failed to fetch data from GitHub.');
            return;
        }

        $repos = $response->json();
        $this->info("Found " . count($repos) . " repositories.");

        // Ensure a default category exists for the projects
        $category = Category::firstOrCreate(
            ['slug' => 'open-source'],
            ['name' => 'Open Source Project', 'description' => 'Automatically synced GitHub repositories.']
        );

        foreach ($repos as $repo) {
            // Skip if it's a fork? Optional. keeping all public repos for now.
            if ($repo['fork']) {
                continue;
            }

            $this->line("Syncing: " . $repo['name']);

            Project::updateOrCreate(
                ['repo_url' => $repo['html_url']], // Unique identifier: URL
                [
                    'category_id' => $category->id,
                    'title' => $repo['name'], // Title
                    'name' => $repo['name'], // Keeping name in sync
                    'slug' => Str::slug($repo['name']),
                    'description' => $repo['description'] ?? '',
                    'summary' => Str::limit($repo['description'] ?? '', 190), // Summary is usually shorter
                    'repo_name' => $repo['full_name'],
                    'github_url' => $repo['html_url'],
                    'stars' => $repo['stargazers_count'],
                    'language' => $repo['language'],
                    'topics' => $repo['topics'] ?? [],
                    'pushed_at' => isset($repo['pushed_at']) ? date('Y-m-d H:i:s', strtotime($repo['pushed_at'])) : null,
                    // 'published_at' map to pushed_at or created_at
                    'created_at' => isset($repo['created_at']) ? date('Y-m-d H:i:s', strtotime($repo['created_at'])) : now(),
                ]
            );
        }

        $this->info('GitHub repositories synced successfully!');
    }
}
