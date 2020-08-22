<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSukienDoitacTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sukien_doitac', function (Blueprint $table) {
            $table->unsignedInteger('sk_ma');
            $table->unsignedInteger('dt_ma');
            $table->unsignedInteger('sk_dt_thanhtoan');

            $table->primary(['sk_ma','dt_ma']);
            $table->foreign('sk_ma')->references('sk_ma')->on('sukien')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('dt_ma')->references('dt_ma')->on('doitac')
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
        Schema::dropIfExists('sukien_doitac');
    }
}
