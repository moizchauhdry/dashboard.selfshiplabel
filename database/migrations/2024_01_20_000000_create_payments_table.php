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
            $table->json('payment_response')->nullable();
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
