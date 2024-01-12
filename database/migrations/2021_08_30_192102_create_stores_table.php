<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('stores')) {
            Schema::create('stores', function (Blueprint $table) {
                $table->id();
                $table->string('name', 32);
                $table->foreignIdFor(\App\Models\Country::class);
                $table->foreignIdFor(\App\Models\City::class);
                $table->string('zip', 10);
                $table->string('state', 32);
                $table->string('phone', 16)->nullable();
                $table->string('address', 255)->nullable();
                $table->string('contact_person', 255)->nullable();
                $table->string('email', 255)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('stores');
    }
}
