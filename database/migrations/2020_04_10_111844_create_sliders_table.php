<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_color');
            $table->text('description')->nullable();
            $table->string('description_color')->nullable();
            $table->string('image');
            $table->string('button_text');
            $table->string('button_link');
            $table->string('button_color')->nullable();
            $table->boolean('box_status')->nullable();;
            $table->string('box_text')->nullable();
            $table->string('box_color')->nullable();
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
        Schema::dropIfExists('sliders');
    }
}
