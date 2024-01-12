<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_boxes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('package_id');
            $table->string('pkg_type', 100);
            $table->enum('weight_unit', ['kg', 'lb'])->default('lb');
            $table->enum('dim_unit', ['cm', 'in'])->default('in');
            $table->float('weight');
            $table->float('length');
            $table->float('width');
            $table->float('height');
            $table->string('tracking_out', 100)->nullable();
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
        Schema::dropIfExists('package_boxes');
    }
}
