<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChitietphieunhapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chitietphieunhap', function (Blueprint $table) {
            $table->string('pn_maphieunhap',50);
            $table->unsignedInteger('dc_ma');
            $table->unsignedInteger('ctpn_soluong');
            $table->decimal('ctpn_dongia',12,2)->unsigned();

            $table->primary(['pn_maphieunhap','dc_ma']);
            $table->foreign('pn_maphieunhap')->references('pn_maphieunhap')->on('phieunhap')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('dc_ma')->references('dc_ma')->on('dungcu')
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
        Schema::dropIfExists('chitietphieunhap');
    }
}
