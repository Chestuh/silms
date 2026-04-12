<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            // avoid errors when columns already exist (migration ran partially outside of framework)
            if (! Schema::hasColumn('pre_registrations', 'applicant_category')) {
                // Applicant Category & Program
                $table->enum('applicant_category', ['grade7', 'grade11', 'grade12', 'transferee', 'returnee'])->nullable()->after('year_level');
                $table->enum('preferred_program', ['ABM', 'GAS', 'STEM', 'TVL-Automotive', 'TVL-ICT', 'TVL-Cookery', 'TVL-HomeEc', 'TVL-IndustrialArts', 'TVL-AgriFishery'])->nullable()->after('applicant_category');

                // Personal Information
                $table->string('last_name')->nullable()->after('preferred_program');
                $table->string('first_name')->nullable()->after('last_name');
                $table->string('middle_name')->nullable()->after('first_name');
                $table->boolean('has_no_middle_name')->default(false)->after('middle_name');
                $table->string('extension_name')->nullable()->after('has_no_middle_name');
                $table->enum('sex', ['Male', 'Female', 'Other'])->nullable()->after('extension_name');
                $table->date('date_of_birth')->nullable()->after('sex');
                $table->string('place_of_birth')->nullable()->after('date_of_birth');
                $table->enum('civil_status', ['Single', 'Married', 'Divorced', 'Widowed'])->nullable()->after('place_of_birth');
                
                // Contact Information
                $table->string('telephone_number')->nullable()->after('civil_status');
                $table->string('mobile_number')->nullable()->after('telephone_number');
                
                // Address Information
                $table->text('permanent_address')->nullable()->after('mobile_number');
                $table->text('current_address')->nullable()->after('permanent_address');
                
                // Citizenship
                $table->enum('citizenship', ['Filipino', 'Dual', 'Other'])->nullable()->after('current_address');
                $table->string('citizenship_other')->nullable()->after('citizenship');
                
                // Family Information (JSON)
                $table->text('family_members_indicator')->nullable()->after('citizenship_other');
                $table->longText('family_information')->nullable()->after('family_members_indicator');
                
                // Academic Information
                $table->string('elementary_graduation_year')->nullable()->after('family_information');
                $table->string('junior_high_graduation_year')->nullable()->after('elementary_graduation_year');
                $table->string('high_school_graduation_year')->nullable()->after('junior_high_graduation_year');
                
                // Emergency Contact Information
                $table->string('emergency_contact_name')->nullable()->after('high_school_graduation_year');
                $table->text('emergency_contact_address')->nullable()->after('emergency_contact_name');
                $table->string('emergency_contact_number')->nullable()->after('emergency_contact_address');
                $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_number');
                $table->string('emergency_contact_email')->nullable()->after('emergency_contact_relationship');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'applicant_category', 'preferred_program', 'last_name', 'first_name', 'middle_name',
                'has_no_middle_name', 'extension_name', 'sex', 'date_of_birth', 'place_of_birth',
                'civil_status', 'telephone_number', 'mobile_number', 'permanent_address', 'current_address',
                'citizenship', 'citizenship_other', 'family_members_indicator', 'family_information',
                'elementary_graduation_year', 'junior_high_graduation_year', 'high_school_graduation_year',
                'emergency_contact_name', 'emergency_contact_address', 'emergency_contact_number',
                'emergency_contact_relationship', 'emergency_contact_email'
            ]);
        });
    }
};
