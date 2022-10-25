<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('employee_num')->nullable();
            $table->string('department');
            $table->string('section');
            $table->string('employment_status');
            $table->string('img_name');

            //*********************************
            // Foreign KEY [ Uncomment if you want to use!! ]
            //*********************************
            //$table->foreign("employee_num")->references("employee_num")->on("Dayworkhours");

            // ----------------------------------------------------
            // -- SELECT [users]--
            // ----------------------------------------------------
            // $query = DB::table("users")
            // ->leftJoin("Dayworkhours","Dayworkhours.employee_num", "=", "users.employee_num")
            // ->get();
            // dd($query); //For checking
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
