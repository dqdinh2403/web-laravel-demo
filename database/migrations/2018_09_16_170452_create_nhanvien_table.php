<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNhanvienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhanvien', function (Blueprint $table) {
            $table->increments('nv_ma');
            $table->string('nv_tennhanvien',50);
            $table->unsignedInteger('nv_gioitinh');
            $table->string('nv_diachi',100);
            $table->string('nv_dienthoai',50);
            $table->string('nv_email',50);
            $table->date('nv_ngaysinh');
            $table->string('nv_cmnd',50)->nullable();
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
        Schema::dropIfExists('nhanvien');
    }
}
