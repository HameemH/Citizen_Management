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
        Schema::create('fake_nids', function (Blueprint $table) {
            $table->id();
            $table->string('nid', 10)->unique(); // 10-digit Bangladeshi NID
            $table->string('name'); // Full name
            $table->string('father_name'); // Father's name
            $table->string('mother_name'); // Mother's name
            $table->date('date_of_birth'); // Date of Birth
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('birth_place')->nullable(); // Place of birth
            $table->text('present_address')->nullable(); // Present address
            $table->text('permanent_address')->nullable(); // Permanent address
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->string('reason')->nullable(); // Reason why it's fake/blocked
            $table->boolean('is_blocked')->default(true); // Whether this NID is blocked/fake
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fake_nids');
    }
};
