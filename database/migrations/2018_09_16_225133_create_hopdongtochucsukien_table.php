<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHopdongtochucsukienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hopdongtochucsukien', function (Blueprint $table) {
            $table->string('hdtcsk_sohopdong',50);
            $table->decimal('hdtcsk_giatrihopdong',12,2)->unsigned();
            $table->decimal('hdtcsk_sotientamung',12,2)->unsigned()->nullable();
            $table->unsignedInteger('hdtcsk_thanhtoan');
            $table->text('hdtcsk_noidunghopdong');
            $table->date('hdtcsk_ngaytaohopdong');
            $table->unsignedInteger('nv_taohopdong');
            $table->date('hdtcsk_ngayxuathopdong')->nullable();
            $table->unsignedInteger('nv_chiutrachnhiem');
            $table->unsignedInteger('hdtcsk_trangthai');
            $table->unsignedInteger('kh_ma');
            $table->unsignedInteger('bm_ma');

            $table->primary('hdtcsk_sohopdong');
            $table->foreign('nv_taohopdong')->references('nv_ma')->on('nhanvien')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('nv_chiutrachnhiem')->references('nv_ma')->on('nhanvien')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kh_ma')->references('kh_ma')->on('khachhang')
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
        Schema::dropIfExists('hopdongtochucsukien');
    }
}
