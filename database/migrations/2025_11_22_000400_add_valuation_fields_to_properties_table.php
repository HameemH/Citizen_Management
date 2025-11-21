<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->decimal('assessed_value', 14, 2)->nullable()->after('rent_price');
            $table->string('land_use', 50)->nullable()->after('assessed_value');
            $table->timestamp('last_valuation_at')->nullable()->after('land_use');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['assessed_value', 'land_use', 'last_valuation_at']);
        });
    }
};
