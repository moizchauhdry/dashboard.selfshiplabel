<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            // The coupon code
            $table->string( 'code' )->nullable( );
            // The human readable coupon code name
            $table->string( 'name' )->nullable();
            // The description of the coupon - Not necessary
            $table->text( 'description' )->nullable( );
            // The number of uses currently
            $table->unsignedInteger( 'uses' )->nullable( );
            // The max uses this coupon has
            $table->unsignedInteger( 'max_uses' )->nullable( );
            // How many times a user can use this coupon.
            $table->unsignedInteger( 'max_uses_user' )->nullable( );
            // The percentage to discount in this.
            $table->integer( 'discount' );
            // Whether or not the coupon is a percentage or a fixed price.
            $table->boolean( 'is_fixed' )->default( true );
            // Whether or not the coupon valid.
            $table->boolean( 'status' )->default( true );
            // When the coupon begins
            $table->timestamp( 'starts_at' )->nullable();
            // When the coupon ends
            $table->timestamp( 'expires_at' )->nullable();
            // You know what this is...
            $table->timestamps( );
            // We like to horde data.
            $table->softDeletes( );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
