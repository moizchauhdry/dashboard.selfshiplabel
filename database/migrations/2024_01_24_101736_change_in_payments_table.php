<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeInPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('square_source_id', 100)->nullable();
            $table->string('square_customer_id', 100)->nullable();
            $table->json('square_customer_response')->nullable();
            $table->string('square_card_id', 100)->nullable();
            $table->json('square_card_response')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('square_source_id');
            $table->dropColumn('square_customer_id');
            $table->dropColumn('square_customer_response');
            $table->dropColumn('square_card_id');
            $table->dropColumn('square_card_response');
        });
    }
}
