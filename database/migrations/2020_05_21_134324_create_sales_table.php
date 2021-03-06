<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->float('discount',10,2);
            $table->float('carrying_and_loading',10,2);
            $table->dateTime('sales_at');
            $table->float('amount',14,2)->nullable();
            $table->integer('sales_status')->default(0);
            $table->string('provided_by')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('delivery_status')->default(0);
            $table->boolean('edited')->default(0);
            $table->integer('delivery_marked_by')->nullable();
            $table->text('deliveryinfo')->nullable();
            $table->boolean('is_condition')->default(0);
            $table->float('condition_amount',16,2)->nullable();
            $table->dateTime('delivered_at')->nullable();
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
        Schema::dropIfExists('sales');
    }
}
