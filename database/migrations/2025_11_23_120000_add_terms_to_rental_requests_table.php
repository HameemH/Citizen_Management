<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rental_requests', function (Blueprint $table) {
            $table->date('tenant_start_date')->nullable()->after('message');
            $table->date('tenant_end_date')->nullable()->after('tenant_start_date');
            $table->decimal('tenant_monthly_rent', 12, 2)->nullable()->after('tenant_end_date');
            $table->decimal('tenant_security_deposit', 12, 2)->nullable()->after('tenant_monthly_rent');

            $table->date('owner_start_date')->nullable()->after('tenant_security_deposit');
            $table->date('owner_end_date')->nullable()->after('owner_start_date');
            $table->decimal('owner_monthly_rent', 12, 2)->nullable()->after('owner_end_date');
            $table->decimal('owner_security_deposit', 12, 2)->nullable()->after('owner_monthly_rent');
            $table->text('owner_notes')->nullable()->after('owner_security_deposit');
            $table->boolean('ready_for_admin')->default(false)->after('owner_notes');
            $table->timestamp('owner_confirmed_at')->nullable()->after('ready_for_admin');
            $table->foreignId('owner_confirmed_by')->nullable()->after('owner_confirmed_at')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('rental_requests', function (Blueprint $table) {
            $table->dropForeign(['owner_confirmed_by']);
            $table->dropColumn([
                'tenant_start_date',
                'tenant_end_date',
                'tenant_monthly_rent',
                'tenant_security_deposit',
                'owner_start_date',
                'owner_end_date',
                'owner_monthly_rent',
                'owner_security_deposit',
                'owner_notes',
                'ready_for_admin',
                'owner_confirmed_at',
                'owner_confirmed_by',
            ]);
        });
    }
};
