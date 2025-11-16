<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Services\GitHubProjectService;
use App\Services\GithubProjectSync;
use Illuminate\Console\Command;

class FetchGitHubProjects extends Command
{
    protected $signature = 'github:fetch-projects';

    protected $description = 'Fetch repositories from GitHub and update the projects timeline.';

    public function handle(GitHubProjectService $service, GithubProjectSync $sync): int
    {
        $this->info('Fetching repositories from GitHub...');

        $service->fetchRepositories();

        $this->info('Syncing project README and commit activity...');

        Project::query()
            ->where(function ($query) {
                $query
                    ->whereNotNull('github_url')
                    ->orWhereNotNull('repo_url')
                    ->orWhereNotNull('repo_name');
            })
            ->chunk(25, function ($projects) use ($sync) {
                foreach ($projects as $project) {
                    $sync->syncReadmeAndCommits($project);
                }
            });

        $this->info('GitHub projects synchronized successfully.');

        return self::SUCCESS;
    }
}
