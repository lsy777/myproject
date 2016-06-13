<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHks extends Migration
{
    /**
     * Run the migrations.
     *还款表
     * @return void
     */
    public function up()
    {
        Schema::create('hks', function (Blueprint $table) {
            $table->increments('hid');
            $table->integer('uid')->unsigned();//贷款用户uid
            $table->integer('pid')->unsigned();//贷款用户pid
            $table->string('title',60);//项目名称
            $table->integer('amount');//每月还款资金
            $table->date('paydate');//还款日期
            $table->tinyinteger('status');//是否已还
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hks');
    }
}
