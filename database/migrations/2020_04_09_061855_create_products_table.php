<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('image')->default('product.jpg');
            $table->float('price', 14, 2);
            $table->float('discount_price', 14, 2)->nullable();
            $table->float('current_price', 14, 2)->nullable();
            $table->foreignId('category_id');
            $table->foreignId('subcategory_id');
            $table->foreignId('brand_id');
            $table->text('description')->nullable();
            $table->integer('size_id');
            $table->string('type');
            $table->string('gallery_image')->nullable();
            $table->boolean('in_stock')->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
