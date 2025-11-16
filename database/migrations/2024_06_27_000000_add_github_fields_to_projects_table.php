<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
            $table->text('description')->nullable()->after('name');
            $table->string('repo_name')->nullable()->after('description');
            $table->string('repo_url')->nullable()->after('repo_name');
            $table->unsignedInteger('stars')->default(0)->after('repo_url');
            $table->json('topics')->nullable()->after('stars');
            $table->string('language')->nullable()->after('topics');
            $table->timestamp('pushed_at')->nullable()->after('language');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'description',
                'repo_name',
                'repo_url',
                'stars',
                'topics',
                'language',
                'pushed_at',
            ]);
        });
    }
};
