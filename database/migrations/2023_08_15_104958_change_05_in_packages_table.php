<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Change05InPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->boolean('return_package')->nullable()->default(false);
            $table->boolean('return_label')->nullable()->default(false);
            $table->string('return_label_file', 255)->nullable();
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
            // $table->dropColumn('return_package');
            $table->dropColumn('return_label');
            $table->dropColumn('return_label_file');
        });
    }
}
