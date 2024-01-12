<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Change09InPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->integer('warehouse_id')->unsigned()->nullable()->change();
            $table->integer('project_id')->unsigned()->default(1);
            $table->integer('ship_from')->unsigned()->nullable();
            $table->integer('ship_to')->unsigned()->nullable();
            $table->longText('encoded_label')->nullable();
            $table->boolean('cart')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('project_id');
            $table->dropColumn('ship_from');
            $table->dropColumn('ship_to');
            $table->dropColumn('encoded_label');
            $table->dropColumn('cart');
        });
    }
}
