<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSudungTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sudung', function (Blueprint $table) {
            $table->unsignedInteger('sk_ma');
            $table->unsignedInteger('dc_ma');
            $table->unsignedInteger('sd_soluongmuon')->nullable();
            $table->date('sd_ngaymuon')->nullable();
            $table->unsignedInteger('nv_muon')->nullable();
            $table->unsignedInteger('sd_soluongtra')->nullable();
            $table->date('sd_ngaytra')->nullable();
            $table->unsignedInteger('nv_tra')->nullable();
            $table->string('sd_ghichu',255)->nullable();
            $table->unsignedInteger('nv_ghinhan')->nullable();
            $table->unsignedInteger('sd_trangthai');

            $table->primary(['sk_ma','dc_ma']);
            $table->foreign('nv_muon')->references('nv_ma')->on('nhanvien')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('nv_tra')->references('nv_ma')->on('nhanvien')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('nv_ghinhan')->references('nv_ma')->on('nhanvien')
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
        Schema::dropIfExists('sudung');
    }
}
