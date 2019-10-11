<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //授權紀錄
        Schema::create('licenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');//唯一编号
            $table->string('key');//授权码
            $table->integer('status')->default(1);//状态 (未激活，已激活，已过期)
            $table->string('remark')->nullable();//备注
            $table->dateTime('expire_at')->nullable();
            $table->string('beneficiary');//授權用戶/域名，查詢時可顯示，例如授權給 YFsama
            $table->timestamps();
        });

        //調用創建API.... 驗證權限使用
        Schema::create('token', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('token');//
            $table->integer('status')->default(1);;//状态
            $table->string('remark')->nullable();//备注
            $table->timestamps();
        });

        //項目，授權對於項目，項目有唯一編碼
        Schema::create('project', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');//唯一编号
            $table->integer('status')->default(1);;//状态
            $table->string('remark')->nullable();//备注
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
        Schema::dropIfExists('licenses');
        Schema::dropIfExists('token');
    }
}
