<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->string('area')->nullable();
            $table->integer('order')->default(0);
            $table->integer('delivery')->default(0);
            $table->string('market')->nullable();
            $table->string('comment')->nullable();
            $table->text('productinfo')->nullable();
            $table->dateTime('at')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('marketing_reports');
    }
}
