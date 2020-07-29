<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnproductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returnproducts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->float('discount',10,2);
            $table->float('carrying_and_loading',10,2);
            $table->float('amount',14,2);
            $table->dateTime('returned_at');
            $table->string('type');
            $table->string('returned_by')->nullable();
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
        Schema::dropIfExists('returnproducts');
    }
}
