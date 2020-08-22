<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSukienCongviecNhanvienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sukien_congviec_nhanvien', function (Blueprint $table) {
            $table->unsignedInteger('sk_ma');
            $table->unsignedInteger('cv_ma');
            $table->unsignedInteger('nv_ma')->nullable();
            $table->unsignedInteger('sk_cv_nv_soluongnhanvien');
            $table->text('sk_cv_nv_ghichu')->nullable();
            $table->unsignedInteger('sk_cv_nv_trangthai');

            $table->primary(['sk_ma','cv_ma']);
            $table->foreign('sk_ma')->references('sk_ma')->on('sukien')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('cv_ma')->references('cv_ma')->on('congviec')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('nv_ma')->references('nv_ma')->on('nhanvien')
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
        Schema::dropIfExists('sukien_congviec_nhanvien');
    }
}
