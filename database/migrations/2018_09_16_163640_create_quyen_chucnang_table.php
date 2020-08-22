<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuyenChucnangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quyen_chucnang', function (Blueprint $table) {
            $table->unsignedInteger('q_ma');
            $table->unsignedInteger('cn_ma');

            $table->primary(['q_ma','cn_ma']);
            $table->foreign('q_ma')->references('q_ma')->on('quyen')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('cn_ma')->references('cn_ma')->on('chucnang')
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
        Schema::dropIfExists('quyen_chucnang');
    }
}
