<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBieumauTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bieumau', function (Blueprint $table) {
            $table->increments('bm_ma');
            $table->string('bm_ten',100);
            $table->text('bm_noidung');
            $table->text('bm_saoluu')->nullable();
            $table->unsignedInteger('bm_trangthai');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bieumau');
    }
}
