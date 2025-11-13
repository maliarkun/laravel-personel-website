<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('email');
            $table->string('avatar_path')->nullable()->after('username');
            $table->text('bio')->nullable()->after('avatar_path');
            $table->string('timezone')->nullable()->after('bio');
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->boolean('is_locked')->default(false)->after('last_login_ip');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'avatar_path',
                'bio',
                'timezone',
                'last_login_at',
                'last_login_ip',
                'is_locked',
            ]);
        });
    }
};
