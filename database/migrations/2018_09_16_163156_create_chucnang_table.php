<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChucnangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chucnang', function (Blueprint $table) {
            $table->increments('cn_ma');
            $table->string('cn_ten',100);
            $table->string('cn_lienket',100);
            $table->string('cn_bieutuong',50);
            $table->unsignedInteger('cn_vitri');
            $table->unsignedInteger('cn_cha')->nullable();
            $table->unsignedInteger('cn_trangthai');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chucnang');
    }
}
