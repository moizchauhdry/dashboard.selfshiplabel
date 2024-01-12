<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableStoresAddWarehouseIdAndPickupChargesColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasColumns('stores', ['warehouse_id', 'pickup_charges'])) {
            Schema::table('stores', function (Blueprint $table) {
                $table->unsignedBigInteger('warehouse_id')->default(0)->after('id');
                $table->float('pickup_charges')->default(0)->after('warehouse_id');
            });

            if (Schema::hasColumn('stores', 'city_id')) {
                Schema::table('stores', function (Blueprint $table) {
                    $table->renameColumn('city_id', 'city');
                });
            }
            Schema::table('stores', function (Blueprint $table) {
                $table->string('city')->change();
            });

            /*if (Schema::hasColumns('stores', ['phone', 'email', 'contact_person'])) {
                Schema::table('stores', function (Blueprint $table) {
                    $table->dropColumn(['phone', 'email', 'contact_person']);
                });
            }*/
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        if (Schema::hasColumns('stores', ['warehouse_id', 'pickup_charges'])) {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropColumn(['warehouse_id', 'pickup_charges']);
            });
        }
    }
}
