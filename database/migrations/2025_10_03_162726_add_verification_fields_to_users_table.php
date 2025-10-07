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
            // Basic NID Information
            $table->string('nid_number', 10)->nullable()->after('verification_status');
            $table->string('full_name')->nullable()->after('nid_number');
            $table->date('date_of_birth')->nullable()->after('full_name');
            $table->string('father_name')->nullable()->after('date_of_birth');
            $table->string('mother_name')->nullable()->after('father_name');
            
            // Address Information
            $table->text('permanent_address')->nullable()->after('mother_name');
            $table->text('present_address')->nullable()->after('permanent_address');
            
            // Contact Information
            $table->string('phone_number', 11)->nullable()->after('present_address');
            
            // Document Images
            $table->string('nid_front_image')->nullable()->after('phone_number');
            $table->string('nid_back_image')->nullable()->after('nid_front_image');
            $table->string('passport_photo')->nullable()->after('nid_back_image');
            
            // Verification Timestamps and References
            $table->timestamp('verification_requested_at')->nullable()->after('passport_photo');
            $table->timestamp('verified_at')->nullable()->after('verification_requested_at');
            $table->unsignedBigInteger('verified_by')->nullable()->after('verified_at');
            $table->timestamp('rejected_at')->nullable()->after('verified_by');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at');
            $table->text('rejection_reason')->nullable()->after('rejected_by');
            
            // Foreign Key Constraints
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes for better performance
            $table->index('nid_number');
            $table->index('verification_status');
            $table->index('verification_requested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['rejected_by']);
            
            // Drop indexes
            $table->dropIndex(['nid_number']);
            $table->dropIndex(['verification_status']);
            $table->dropIndex(['verification_requested_at']);
            
            // Drop columns
            $table->dropColumn([
                'nid_number',
                'full_name',
                'date_of_birth',
                'father_name',
                'mother_name',
                'permanent_address',
                'present_address',
                'phone_number',
                'nid_front_image',
                'nid_back_image',
                'passport_photo',
                'verification_requested_at',
                'verified_at',
                'verified_by',
                'rejected_at',
                'rejected_by',
                'rejection_reason',
            ]);
        });
    }
};
