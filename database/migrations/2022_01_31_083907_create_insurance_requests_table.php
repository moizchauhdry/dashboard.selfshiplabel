<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('shipping_service')->nullable();
            $table->double('insurance_amount',10,2)->nullable();
            $table->string('message')->nullable();
            // total amount to deduct ...
            $table->double('amount',10,2)->nullable();
            $table->boolean('is_approved')->default(false);
            $table->enum('payment_status',['Pending','Paid','Unsuccessful'])->default('Pending');
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
        Schema::dropIfExists('insurance_requests');
    }
}
