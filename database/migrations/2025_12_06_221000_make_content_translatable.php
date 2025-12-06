<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing data to be valid JSON for the 'tr' locale before changing column type

        // Projects
        // Note: github sync overwrites these, but for existing manual data we assume TR
        if (Schema::hasTable('projects')) {
            // Check if column exists before trying to update
            if (Schema::hasColumn('projects', 'title')) {
                DB::statement("UPDATE projects SET title = JSON_OBJECT('tr', title) WHERE title IS NOT NULL AND JSON_VALID(title) = 0");
            }
            if (Schema::hasColumn('projects', 'description')) {
                DB::statement("UPDATE projects SET description = JSON_OBJECT('tr', description) WHERE description IS NOT NULL AND JSON_VALID(description) = 0");
            }
            if (Schema::hasColumn('projects', 'summary')) {
                DB::statement("UPDATE projects SET summary = JSON_OBJECT('tr', summary) WHERE summary IS NOT NULL AND JSON_VALID(summary) = 0"); // summary might be null
            }
        }

        // Notes (Assuming table is 'notes' based on Note model)
        if (Schema::hasTable('notes')) {
            if (Schema::hasColumn('notes', 'title')) {
                DB::statement("UPDATE notes SET title = JSON_OBJECT('tr', title) WHERE title IS NOT NULL AND JSON_VALID(title) = 0");
            }
            if (Schema::hasColumn('notes', 'content')) {
                DB::statement("UPDATE notes SET content = JSON_OBJECT('tr', content) WHERE content IS NOT NULL AND JSON_VALID(content) = 0");
            }
        }

        Schema::table('projects', function (Blueprint $table) {
            $table->json('title')->change();
            $table->json('description')->nullable()->change();
            $table->json('summary')->nullable()->change();
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->json('title')->change();
            $table->json('content')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting this is complex because we lose information about which language was original if we just flatten.
        // We will just convert back to text/string

        Schema::table('projects', function (Blueprint $table) {
            $table->string('title')->change();
            $table->text('description')->nullable()->change();
            $table->text('summary')->nullable()->change();
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->string('title')->change();
            $table->text('content')->change();
        });
    }
};
