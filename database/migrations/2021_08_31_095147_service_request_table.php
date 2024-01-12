<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ServiceRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id(); 
            $table->integer('service_id');
            $table->integer('package_id');
            $table->tinyInteger('price');
            $table->enum('status',['pending','served','rejected'])->default('pending'); 
            $table->text('admin_message')->nullable(); 
            $table->text('customer_message')->nullable(); 
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
        Schema::dropIfExists('service_requests');
    }
}
