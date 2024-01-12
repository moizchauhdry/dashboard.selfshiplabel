<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeInPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->enum('pkg_type', ['single', 'multipiece', 'consolidation', 'assigned'])->nullable();
            $table->enum('admin_status', ['pending', 'accepted', 'rejected'])->nullable()->default('pending');
            $table->enum('pkg_dim_status', ['pending', 'done'])->nullable()->default('pending');
            $table->string('tracking_number_in', 100)->nullable();
            $table->string('received_from', 100)->nullable();
            $table->text('notes')->nullable();
            $table->double('consolidation_fee')->nullable();
            $table->enum('address_type', ['domestic', 'international'])->nullable();
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
            $table->dropColumn('pkg_type');
            $table->dropColumn('admin_status');
            $table->dropColumn('pkg_dim_status');
            $table->dropColumn('tracking_number_in');
            $table->dropColumn('received_from');
            $table->dropColumn('notes');
            $table->dropColumn('consolidation_fee');
            $table->dropColumn('address_type');
        });
    }
}
