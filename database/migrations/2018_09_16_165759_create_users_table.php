<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('tk_ma');
            $table->string('tk_tendangnhap',50)->unique();
            $table->string('tk_matkhau',50);
            $table->string('tk_makichhoat',100)->nullable();
            $table->unsignedInteger('tk_trangthai');
            $table->unsignedInteger('q_ma');
            $table->rememberToken();

            $table->foreign('q_ma')->references('q_ma')->on('quyen')
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
        Schema::dropIfExists('users');
    }
}
