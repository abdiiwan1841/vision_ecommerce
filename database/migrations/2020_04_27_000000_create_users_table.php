<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('proprietor')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('inventory_email')->unique()->nullable();
            $table->string('custom_email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address');
            $table->string('company')->nullable();
            $table->foreignId('division_id')->references('id')->on('divisions');
            $table->foreignId('district_id')->references('id')->on('districts');
            $table->foreignId('area_id')->references('id')->on('areas');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('user_type')->default('ecom');
            $table->foreignId('section_id')->references('id')->on('sections')->nullable();
            $table->string('image')->default('user.jpg');
            $table->boolean('status')->default(1);
            $table->text('pricedata')->nullable();
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
