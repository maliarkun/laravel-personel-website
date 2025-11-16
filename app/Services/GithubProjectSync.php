<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GithubProjectSync
{
    public function syncReadmeAndCommits(Project $project): void
    {
        $fullName = $this->resolveRepositoryFullName($project);

        if (!$fullName) {
            return;
        }

        $token = config('services.github.token');
        $http = Http::withHeaders($this->headers($token));

        $readmeResponse = $http->get("https://api.github.com/repos/{$fullName}/readme");

        if ($readmeResponse->ok()) {
            $content = $readmeResponse->json('content');
            $project->github_readme = $content ? base64_decode($content) : null;
        }

        $commitsResponse = $http->get("https://api.github.com/repos/{$fullName}/commits", [
            'per_page' => 5,
        ]);

        if ($commitsResponse->ok()) {
            $project->github_commits = collect($commitsResponse->json())
                ->map(function (array $commit) {
                    return [
                        'sha' => $commit['sha'] ?? null,
                        'message' => data_get($commit, 'commit.message'),
                        'date' => data_get($commit, 'commit.author.date'),
                        'url' => $commit['html_url'] ?? null,
                        'author' => data_get($commit, 'commit.author.name'),
                    ];
                })
                ->all();
        }

        $project->save();
    }

    protected function resolveRepositoryFullName(Project $project): ?string
    {
        $candidates = [
            $project->github_url,
            $project->repo_url,
        ];

        foreach ($candidates as $candidate) {
            if ($candidate && $fullName = $this->parseFullNameFromUrl($candidate)) {
                return $fullName;
            }
        }

        if ($project->repo_name) {
            $owner = config('services.github.username');

            if ($owner) {
                return $owner.'/'.ltrim($project->repo_name, '/');
            }
        }

        return null;
    }

    protected function parseFullNameFromUrl(string $url): ?string
    {
        $path = trim(parse_url($url, PHP_URL_PATH) ?? '', '/');

        if (empty($path)) {
            return null;
        }

        $segments = explode('/', $path);

        if (count($segments) >= 2) {
            return $segments[0].'/'.Str::replace('.git', '', $segments[1]);
        }

        return null;
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
}
