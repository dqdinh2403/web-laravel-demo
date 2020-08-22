<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDungcuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dungcu', function (Blueprint $table) {
            $table->increments('dc_ma');
            $table->string('dc_ten',100);
            $table->string('dc_mota',255)->nullable();
            $table->unsignedInteger('dc_soluongtong');
            $table->unsignedInteger('dc_soluongconlai');
            $table->unsignedInteger('dc_trangthai');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dungcu');
    }
}
