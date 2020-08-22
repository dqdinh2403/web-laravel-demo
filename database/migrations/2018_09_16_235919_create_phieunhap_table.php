<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhieunhapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phieunhap', function (Blueprint $table) {
            $table->string('pn_maphieunhap',50);
            $table->date('pn_ngaynhap');
            $table->unsignedInteger('nv_lapphieu');
            $table->date('pn_ngayxuatphieu')->nullable();
            $table->unsignedInteger('pn_trangthai');
            $table->unsignedInteger('ncc_ma');
            $table->unsignedInteger('bm_ma');

            $table->primary('pn_maphieunhap');
            $table->foreign('nv_lapphieu')->references('nv_ma')->on('nhanvien')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ncc_ma')->references('ncc_ma')->on('nhacungcap')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('bm_ma')->references('bm_ma')->on('bieumau')
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
        Schema::dropIfExists('phieunhap');
    }
}
