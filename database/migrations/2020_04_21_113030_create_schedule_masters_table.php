<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_masters', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('repeat_type')->default(0);
            $table->unsignedTinyInteger('week_repeat')->default(0);
            $table->unsignedTinyInteger('weekday')->default(1);
            $table->unsignedTinyInteger('monthday')->default(10);
            $table->date('from_date');
            $table->date('to_date');
            $table->time('from_time');
            $table->time('to_time');
            $table->string('timezone');
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
        Schema::dropIfExists('schedule_masters');
    }
}
