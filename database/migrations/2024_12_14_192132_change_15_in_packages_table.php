<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Change15InPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->boolean('cancelled')->nullable()->default(false);
            $table->dateTime('cancelled_at')->nullable();
            $table->integer('cancelled_by')->unsigned()->nullable();
            $table->string('cancelled_reason')->nullable();
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
            $table->dropColumn('cancelled');
            $table->dropColumn('cancelled_at');
            $table->dropColumn('cancelled_by');
            $table->dropColumn('cancelled_reason');
        });
    }
}
