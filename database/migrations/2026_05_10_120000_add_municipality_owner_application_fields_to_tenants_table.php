<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection(config('multitenancy.landlord_database_connection_name', 'landlord'))->table('tenants', function (Blueprint $table) {
            $table->string('municipality_business_permit_path')->nullable();
            $table->string('municipality_mayors_permit_path')->nullable();
            $table->string('municipality_barangay_clearance_path')->nullable();
            $table->string('municipality_valid_id_path')->nullable();
            $table->timestamp('municipality_requirements_submitted_at')->nullable();
            $table->text('municipality_admin_review_notes')->nullable();
            $table->timestamp('municipality_compliance_verified_at')->nullable();
            $table->text('municipality_compliance_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::connection(config('multitenancy.landlord_database_connection_name', 'landlord'))->table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'municipality_business_permit_path',
                'municipality_mayors_permit_path',
                'municipality_barangay_clearance_path',
                'municipality_valid_id_path',
                'municipality_requirements_submitted_at',
                'municipality_admin_review_notes',
                'municipality_compliance_verified_at',
                'municipality_compliance_notes',
            ]);
        });
    }
};
