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
        Schema::create('attendance_details', function (Blueprint $table) {
            $table->id();
            $table->integer('attendance_head_id');
            $table->string('day',2);
            $table->string('kbn',1);
            $table->time('start_time');
            $table->time('end_time');
            $table->time('rest_time');
            $table->time('night_rest_time');
            $table->time('work_time');
            $table->time('over_time');
            $table->string('remarks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_details');
    }
};
