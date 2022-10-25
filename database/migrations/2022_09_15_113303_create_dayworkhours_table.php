<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDayworkhoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dayworkhours', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('employee_num')->nullable();
            $table->date('date')->nullable();
            $table->time('starttime')->nullable();
            $table->time('endtime')->nullable();
            $table->decimal('resthours', 3, 2)->nullable();
            $table->decimal('workhours_each_day', 4, 2)->nullable();
            $table->decimal('workhours_each_month', 5, 2)->nullable();
            $table->decimal('workhours_each_year', 5, 2)->nullable();
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
        Schema::dropIfExists('dayworkhours');
    }
}
