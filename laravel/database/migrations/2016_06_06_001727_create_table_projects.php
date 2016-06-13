<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('pid');
            $table->integer('uid');
            $table->string('name',60);
            $table->integer('money')->unsigned();;
            $table->string('mobile',11);
            $table->string('title',60);
            $table->tinyinteger('rate')->unsigned();
            $table->tinyinteger('hrange')->unsigned();
            $table->tinyinteger('status');
            $table->integer('revice')->unsigned();
            $table->integer('pubtime')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('projects');
    }
}
