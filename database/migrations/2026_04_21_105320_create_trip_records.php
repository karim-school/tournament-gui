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
        Schema::create('stations', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned();
            $table->tinyInteger('sub_id')->unsigned();
            $table->string('name');

            $table->primary(['id', 'sub_id']);
        });

        Schema::create('trip_records', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary();
            $table->string('rideable_type');
            $table->dateTime('started_at');
            $table->dateTime('ended_at');
            $table->smallInteger('start_station_id');
            $table->tinyInteger('start_station_sub_id');
            $table->smallInteger('end_station_id');
            $table->tinyInteger('end_station_sub_id');
            $table->float('start_location_latitude');
            $table->float('start_location_longitude');
            $table->float('end_location_latitude');
            $table->float('end_location_longitude');
            $table->string('member_casual');

            $table->foreign([ 'start_station_id', 'start_station_sub_id' ])
                ->references([ 'id', 'sub_id' ])->on('stations');

            $table->foreign([ 'end_station_id', 'end_station_sub_id' ])
                ->references([ 'id', 'sub_id' ])->on('stations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_records');
        Schema::dropIfExists('stations');
    }
};
