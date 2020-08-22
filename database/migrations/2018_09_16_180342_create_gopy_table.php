<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGopyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gopy', function (Blueprint $table) {
            $table->increments('gy_ma');
            $table->string('gy_tieude',100);
            $table->string('gy_noidung',255);
            $table->unsignedInteger('gy_trangthai');
            $table->unsignedInteger('tk_ma')->nullable();

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
        Schema::dropIfExists('gopy');
    }
}
