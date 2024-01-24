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
            $table->enum('payment_module', ['package', 'order', 'invoice', 'gift', 'insurance'])->nullable()->default('package');
            $table->integer('payment_module_id')->unsigned()->nullable();
            $table->integer('customer_id');
            $table->string('transaction_id');
            $table->enum('payment_method', ['square', 'authorize', 'paypal', 'stripe'])->nullable()->default('square');
            $table->decimal('charged_amount', 8, 2);
            $table->dateTime('charged_at')->nullable();
            
            // SQUARE
            $table->string('sq_customer_id', 100)->nullable();
            $table->json('sq_customer_response')->nullable();
            $table->string('sq_card_id', 100)->nullable();
            $table->json('sq_card_response')->nullable();
            $table->string('sq_payment_id', 100)->nullable();
            $table->json('sq_payment_response')->nullable();
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
