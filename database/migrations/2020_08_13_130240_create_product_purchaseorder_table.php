<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPurchaseorderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_purchaseorder', function (Blueprint $table) {
            $table->foreignId('purchaseorder_id');
            $table->foreignId('product_id');
            $table->foreignId('supplier_id');
            $table->integer('qty');
            $table->string('price');
            $table->dateTime('po_at');
            $table->timestamps();
            $table->foreign('purchaseorder_id')->references('id')->on('purchaseorders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_purchaseorder');
    }
}
