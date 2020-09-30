<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id');
            $table->float('discount',10,2);
            $table->float('carrying_and_loading',10,2);
            $table->dateTime('purchased_at');
            $table->dateTime('mfg')->nullable();
            $table->dateTime('exp')->nullable();
            $table->string('bacthno')->nullable();
            $table->float('amount',14,2)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
