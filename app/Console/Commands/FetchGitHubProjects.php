<?php

namespace App\Console\Commands;

use App\Services\GitHubProjectService;
use Illuminate\Console\Command;

class FetchGitHubProjects extends Command
{
    protected $signature = 'github:fetch-projects';

    protected $description = 'Fetch repositories from GitHub and update the projects timeline.';

    public function handle(GitHubProjectService $service): int
    {
        $this->info('Fetching repositories from GitHub...');

        $service->fetchRepositories();

        $this->info('GitHub projects synchronized successfully.');

        return self::SUCCESS;
    }
}
