<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('tid');//主键
            $table->integer('uid')->unsigned();//对应投资uid
            $table->integer('pid')->unsigned();//对应项目pid
            $table->string('title',60);//项目名称
            $table->integer('amount');//每月还款
            $table->date('enddate');//项目结束日期
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tasks');
    }
}
