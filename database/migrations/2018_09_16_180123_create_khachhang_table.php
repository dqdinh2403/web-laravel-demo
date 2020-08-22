<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKhachhangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khachhang', function (Blueprint $table) {
            $table->increments('kh_ma');
            $table->string('kh_tencongty',100);
            $table->string('kh_nguoidaidien',50);
            $table->string('kh_diachi',100);
            $table->string('kh_dienthoai',50);
            $table->string('kh_email',50);
            $table->unsignedInteger('tk_ma');

            $table->foreign('tk_ma')->references('tk_ma')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('khachhang');
    }
}
