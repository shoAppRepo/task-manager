<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('manhours', function (Blueprint $table) {
        $table->increments('man_hour_id');
        $table->string('title');
        $table->integer('task_id');
        $table->string('start');
        $table->string('end');
        $table->string('remark')->nullable();
        $table->timestamps();

        $table->foreign('task_id')->references('task_id')->on('tasks')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('manhours');
    }
}
