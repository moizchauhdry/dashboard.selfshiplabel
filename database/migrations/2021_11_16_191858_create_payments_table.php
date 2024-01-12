<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->unsignedBigInteger('additional_request_id')->nullable();
            $table->unsignedBigInteger('insurance_id')->nullable();
            $table->uuid('invoice_id')->nullable();
            $table->string('transaction_id');
            $table->string('card_last4')->nullable();
            $table->string('card_type')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('invoice_url')->nullable();
            $table->double('charged_amount',10,2);
            $table->double('discount',10,2)->default(0.00);
            $table->timestamp('charged_at')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
