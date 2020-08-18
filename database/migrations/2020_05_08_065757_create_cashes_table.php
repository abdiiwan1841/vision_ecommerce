<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashes', function (Blueprint $table) {
            $table->id();
            $table->float('amount', 14, 2);
            $table->foreignId('user_id');
            $table->string('reference');
            $table->foreignId('paymentmethod_id');
            $table->string('posted_by');
            $table->dateTime('received_at');
            $table->integer('status')->default(0);
            $table->integer('approved_by')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('paymentmethod_id')->references('id')->on('paymentmethods');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cashes');
    }
}
