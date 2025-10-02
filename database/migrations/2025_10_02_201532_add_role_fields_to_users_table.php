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
            $table->string('phone')->nullable()->after('email');
            $table->string('national_id')->nullable()->after('phone');
            $table->enum('role', ['admin', 'citizen'])->default('citizen')->after('national_id');
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending')->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'national_id', 'role', 'verification_status']);
        });
    }
};
