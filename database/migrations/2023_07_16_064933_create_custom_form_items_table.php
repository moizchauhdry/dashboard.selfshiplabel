<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFormItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_form_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('custom_id')->unsigned();
            $table->string('desc', 100)->nullable();
            $table->string('code', 100)->nullable();
            $table->string('qty', 100)->nullable();
            $table->string('price', 100)->nullable();
            $table->string('origin', 100)->nullable();
            $table->string('battery', 100)->nullable();
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
        Schema::dropIfExists('custom_form_items');
    }
}
