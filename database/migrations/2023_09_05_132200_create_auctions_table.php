<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('auction_category_id')->nullable();
            $table->string('thumbnail')->nullable();
            $table->double('weight',10,2)->default(0.00);
            $table->enum('weight_unit',['lb','kg']);
            $table->double('length',10,2)->nullable();
            $table->double('width',10,2)->nullable();
            $table->double('height',10,2)->nullable();
            $table->enum('dimension_unit',['in','cm']);
            $table->double('starting_price',10,2)->nullable();
            $table->timestamp('ending_at');
            $table->unsignedInteger('status')->default(1);
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
        Schema::dropIfExists('auctions');
    }
}
