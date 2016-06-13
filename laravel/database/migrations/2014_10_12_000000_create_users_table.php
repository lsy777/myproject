<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('uid');//主鍵
            $table->string('name');//用戶名
            $table->string('email')->unique();//邮箱
            $table->char('mobile',11);//手机号
            $table->string('password', 60);//密码
            $table->integer('regtime');//注册时间
            $table->integer('lasttime');//最后登录时间
            $table->rememberToken();//记录用户cookie
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
