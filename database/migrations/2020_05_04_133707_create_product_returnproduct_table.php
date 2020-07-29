<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductReturnproductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_returnproduct', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->foreignId('returnproduct_id');
            $table->foreignId('user_id');
            $table->integer('qty');
            $table->string('price');
            $table->dateTime('returned_at');
            $table->timestamps();
            $table->foreign('returnproduct_id')->references('id')->on('returnproducts')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_returnproduct');
    }
}
