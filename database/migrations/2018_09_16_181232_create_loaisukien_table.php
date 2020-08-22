<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoaisukienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loaisukien', function (Blueprint $table) {
            $table->increments('lsk_ma');
            $table->string('lsk_ten',100);
            $table->string('lsk_mota',255)->nullable();
            $table->unsignedInteger('lsk_trangthai');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loaisukien');
    }
}
