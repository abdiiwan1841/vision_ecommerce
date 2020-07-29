<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->float('discount', 10, 2);
            $table->float('vat', 10, 2);
            $table->float('tax', 10, 2);
            $table->integer('shipping');
            $table->integer('payment_method');
            $table->boolean('payment_status');
            $table->boolean('shipping_status');
            $table->date('shipping_date')->nullable();
            $table->integer('order_status');
            $table->string('invoice_id');
            $table->float('cash', 15, 2)->default(0);
            $table->float('amount', 15, 2)->default(0);
            $table->string('txn_id')->nullable();
            $table->string('references')->nullable();
            $table->dateTime('shipped_at')->nullable();
            $table->dateTime('ordered_at');
            $table->dateTime('paymented_at')->nullable();
            $table->text('address');
            $table->string('posted_by')->nullable();
            $table->string('approval_info')->nullable();
            $table->string('cancelation_info')->nullable();
            $table->foreignId('division_id')->references('id')->on('divisions');
            $table->foreignId('district_id')->references('id')->on('districts');
            $table->foreignId('area_id')->references('id')->on('areas');
            $table->softDeletes();
            $table->timestamps();
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
        Schema::dropIfExists('orders');
    }
}
