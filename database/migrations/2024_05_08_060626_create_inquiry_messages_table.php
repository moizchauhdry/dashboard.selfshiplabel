<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInquiryMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiry_messages', function (Blueprint $table) {
            $table->id();
            $table->integer('inquiry_id')->unsigned()->nullable()->default(12);
            $table->integer('user_id')->unsigned()->nullable()->default(12);
            $table->enum('user_type', ['admin', 'customer'])->nullable()->default('customer');
            $table->text('message')->nullable();
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
        Schema::dropIfExists('inquiry_messages');
    }
}
