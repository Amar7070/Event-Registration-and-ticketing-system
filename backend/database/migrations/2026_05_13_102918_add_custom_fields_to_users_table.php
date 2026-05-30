<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();
            $table->string('google_id')->nullable();
            $table->string('github_id')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_secret')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('preferred_language')->default('en');
            $table->string('timezone')->default('Asia/Kolkata');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'avatar', 'google_id', 'github_id', 
                'two_factor_enabled', 'two_factor_secret', 
                'is_active', 'preferred_language', 'timezone'
            ]);
        });
    }
};
