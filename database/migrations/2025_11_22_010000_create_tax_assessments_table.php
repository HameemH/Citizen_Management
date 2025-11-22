<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tax_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('fiscal_year', 9);
            $table->decimal('assessed_value_snapshot', 14, 2);
            $table->string('land_use_snapshot', 50)->nullable();
            $table->decimal('tax_rate', 6, 4);
            $table->decimal('tax_amount', 14, 2);
            $table->enum('status', ['draft', 'issued', 'overdue', 'paid', 'cancelled'])->default('draft');
            $table->date('due_date')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['property_id', 'fiscal_year'], 'tax_assessments_property_year_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_assessments');
    }
};
