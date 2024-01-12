<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_forms', function (Blueprint $table) {
            $table->id();
            $table->string('ship_from_company', 100)->nullable();
            $table->string('ship_from_person', 100)->nullable();
            $table->string('ship_from_contact', 100)->nullable();
            $table->string('ship_from_email', 100)->nullable();
            $table->string('ship_from_address', 100)->nullable();
            $table->string('ship_from_city', 100)->nullable();
            $table->string('ship_from_state', 100)->nullable();
            $table->string('ship_from_zipcode', 100)->nullable();
            $table->string('ship_from_country', 100)->nullable();
            $table->string('ship_from_incoterms', 100)->nullable();

            $table->string('ship_to_company', 100)->nullable();
            $table->string('ship_to_person', 100)->nullable();
            $table->string('ship_to_contact', 100)->nullable();
            $table->string('ship_to_email', 100)->nullable();
            $table->string('ship_to_address1', 100)->nullable();
            $table->string('ship_to_address2', 100)->nullable();
            $table->string('ship_to_address3', 100)->nullable();
            $table->string('ship_to_tax_id', 100)->nullable();
            $table->string('ship_to_city', 100)->nullable();
            $table->string('ship_to_state', 100)->nullable();
            $table->string('ship_to_zipcode', 100)->nullable();
            $table->string('ship_to_country', 100)->nullable();

            $table->string('tracking_number', 100)->nullable();
            $table->date('package_date')->nullable();
            $table->string('package_no', 100)->nullable();
            $table->string('package_type', 100)->nullable();
            $table->float('package_weight')->nullable();
            $table->float('shipping_total')->nullable();
            $table->string('special_instructions', 100)->nullable();
            $table->string('sold_to', 100)->nullable();

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
        Schema::dropIfExists('custom_forms');
    }
}
