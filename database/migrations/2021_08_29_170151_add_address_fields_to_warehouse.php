<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressFieldsToWarehouse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->string('state',32)->nullable();
            $table->string('city',32)->nullable();
            $table->string('phone',16)->nullable();
            $table->string('address',255)->nullable();
            $table->string('contact_person',255)->nullable();
            $table->string('email',255)->nullable();
            $table->string('sale_tax',255)->nullable();
            $table->string('signature',255)->nullable();
            $table->string('signature_name',255)->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
            Schema::table('warehouses', function (Blueprint $table) {
                $table->dropColumn('state');
                $table->dropColumn('city');
                $table->dropColumn('phone');
                $table->dropColumn('address');
                $table->dropColumn('contact_person');
                $table->dropColumn('email');
                $table->dropColumn('sale_tax');
            });
    }
}
