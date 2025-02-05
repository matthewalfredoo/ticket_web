<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->string('kode_payment');
            $table->string('kode_trx');
            $table->integer('total_item')->unsigned();
            $table->bigInteger('total_harga')->unsigned()->nullable();
            $table->integer('kode_unik')->unsigned();
            $table->string('status')->nullable();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->date('detail_tanggal')->nullable();//lokasi kian
            $table->string('deskripsi')->nullable();
            $table->string('bukti_transfer')->nullable();
            $table->bigInteger('total_transfer')->unsigned()->nullable();
            $table->string('bank')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksis');
    }
}
