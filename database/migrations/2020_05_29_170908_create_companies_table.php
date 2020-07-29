<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('Example Company');
            $table->text('address')->nullable();
            $table->string('email')->default('info@example.com');
            $table->string('phone')->default('01xxxxxxxxx');
            $table->string('bin')->default('b54000000000');
            $table->string('social')->default('{"facebook":["#","1"],"twitter":["#","1"],"pinterest":["#","1"],"linkedin":["#","1"]}');
            $table->string('logo')->default('vcl.png');
            $table->string('favicon')->default('favicon.png');
            $table->text('map_embed')->nullable();
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
        Schema::dropIfExists('companies');
    }
}
