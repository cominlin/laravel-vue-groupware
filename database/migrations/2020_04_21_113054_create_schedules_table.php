<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('creator_id');
            $table->unsignedInteger('editor_id');
            $table->unsignedInteger('schedule_category_id');
            $table->unsignedInteger('schedule_master_id')->nullable();
            $table->string('title');
            $table->unsignedTinyInteger('type')->default(0); // 0:通常, 1:期間
            $table->dateTime('from')->nullable();
            $table->dateTime('to')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->text('memo')->nullable();
            $table->unsignedTinyInteger('state')->default(1); // 0:削除, 1:通常
            $table->unsignedInteger('deleter_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
