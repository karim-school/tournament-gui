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
        Schema::table('stations', function (Blueprint $table) {
            $table->float('latitude');
            $table->float('longitude');
        });

        Schema::table('trip_records', function (Blueprint $table) {
            $table->dropColumn('start_location_latitude');
            $table->dropColumn('start_location_longitude');
            $table->dropColumn('end_location_latitude');
            $table->dropColumn('end_location_longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_records', function (Blueprint $table) {
            $table->float('start_location_latitude');
            $table->float('start_location_longitude');
            $table->float('end_location_latitude');
            $table->float('end_location_longitude');
        });

        Schema::table('stations', function (Blueprint $table) {
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
};
