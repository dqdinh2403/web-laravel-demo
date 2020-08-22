<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoitacTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doitac', function (Blueprint $table) {
            $table->increments('dt_ma');
            $table->string('dt_tencongty',100);
            $table->string('dt_nguoidaidien',50);
            $table->string('dt_diachi',100);
            $table->string('dt_dienthoai',50);
            $table->string('dt_email',50);
            $table->unsignedInteger('dt_trangthai');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doitac');
    }
}
