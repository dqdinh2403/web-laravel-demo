<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNhacungcapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhacungcap', function (Blueprint $table) {
            $table->increments('ncc_ma');
            $table->string('ncc_ten',100);
            $table->string('ncc_diachi',100);
            $table->string('ncc_dienthoai',50);
            $table->string('ncc_email',50);
            $table->unsignedInteger('ncc_trangthai');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nhacungcap');
    }
}
