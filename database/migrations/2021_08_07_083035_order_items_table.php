<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->string('name',64);               
            $table->string('description',255)->nullable();              
            $table->integer('quantity')->default(1);
            $table->string('image',255)->nullable(); 
            $table->double('unit_price',10,2)->default(0);
            $table->double('price_with_tax',10,2)->nullable();
            $table->double('sub_total',10,2)->nullable();
            $table->integer('origin_country')->default(0);
            $table->integer('batteries')->default(0);
            $table->string('url',255)->nullable();
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
        Schema::dropIfExists('order_items');
    }
    
}
