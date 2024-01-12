<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('orders');
        
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('warehouse_id');
            $table->integer('package_id');
            $table->string('tracking_number_in',32)->nullable();
            $table->string('tracking_number_out',32)->nullable();
            $table->enum('status', ['pending', 'arrived', 'labeled', 'shipped', 'delivered', 'rejected'])
                ->default('arrived');
            $table->integer('package_weight')->default(0);
            $table->enum('weight_unit',['lb','kg'])->default('lb');
            $table->integer('package_length')->default(0);
            $table->integer('package_width')->default(0);
            $table->integer('package_height')->default(0);
            $table->enum('dim_unit',['in','cm'])->default('in');
            $table->integer('declared_value')->default(0);
            $table->text('notes')->nullable();
            $table->enum('order_type', ['order', 'shopping', 'pickup'])->default('order');
            $table->unsignedBigInteger('store_id')->default(0);
            $table->string('order_origin', 15)->default('order');
            $table->string('site_name',255)->nullable();
            $table->string('site_url',255)->nullable();
            $table->double('shipping_from_shop',10,2)->nullable();
            $table->double('sales_tax',10,2)->nullable();
            $table->boolean('only_pickup')->default(0);
            $table->boolean('shipping_xps_purchase')->default(0);
            $table->date('pickup_date')->nullable();
            $table->double('discount',10,2)->nullable();
            $table->double('shipping_charges',10,2)->nullable();
            $table->double('grand_total',10,2)->nullable();
            $table->double('sub_total',10,2)->nullable();
            $table->double('service_charges',10,2)->nullable();
            $table->boolean('is_service_charges')->default(0);
            $table->string('store_name')->nullable();
            $table->string('pickup_type')->nullable();
            $table->string('pickup_charges')->nullable();
            $table->string('receipt_url')->nullable();
            $table->boolean('is_changed')->nullable();
            $table->boolean('updated_by_admin')->nullable();
            $table->boolean('changes_approved')->default(false);
            $table->enum('payment_status',['Pending','Paid','Unsuccessful'])->default('Pending');
            $table->timestamp('arrived_at')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
