<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSukienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sukien', function (Blueprint $table) {
            $table->increments('sk_ma');
            $table->string('sk_ten',255);
            $table->string('sk_diadiem',100);
            $table->string('sk_toado',100);
            $table->date('sk_thoigianbatdaud');
            $table->time('sk_thoigianbatdaut');
            $table->unsignedInteger('sk_thoiluong');
            $table->text('sk_noidungsukien');
            $table->decimal('sk_kinhphi',12,2)->unsigned();
            $table->unsignedInteger('sk_hienthitrangchu');
            $table->unsignedInteger('sk_trangthai');
            $table->unsignedInteger('lsk_ma');
            $table->string('hdtcsk_sohopdong',50);

            $table->foreign('lsk_ma')->references('lsk_ma')->on('loaisukien')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('hdtcsk_sohopdong')->references('hdtcsk_sohopdong')->on('hopdongtochucsukien')
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
        Schema::dropIfExists('sukien');
    }
}
