<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any existing citizenship values to match enum values
        DB::table('users')->update(['citizenship' => 'Kenyan Citizen']);

        Schema::table('users', function (Blueprint $table) {
            // Change citizenship from string to enum
            $table->enum('citizenship', [
                'Kenyan Citizen',
                'Kenyan Resident',
                'East Africa Resident',
                'Non Resident'
            ])->default('Kenyan Citizen')->change();

            // Add profile image URL
            $table->text('profile_image_url')->nullable()->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert citizenship back to string
            $table->string('citizenship')->change();

            // Drop profile image URL
            $table->dropColumn('profile_image_url');
        });
    }
};
