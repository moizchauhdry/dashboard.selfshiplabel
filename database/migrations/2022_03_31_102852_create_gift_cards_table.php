<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->string('title');
            $table->double('amount',10,2);
            $table->integer('qty')->unsigned()->nullable();
            $table->string('type', 100);
            $table->text('notes')->nullable();
            $table->string('file_url', 100)->nullable();
            $table->boolean('is_approved')->default(false);
            $table->enum('payment_status',['Pending','Paid','Unsuccessful'])->default('Pending');
            $table->timestamp('admin_approved_at')->nullable();
            $table->timestamp('admin_updated_at')->nullable();
            $table->enum('status', ['Accepted', 'Rejected'])->nullable();
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
        Schema::dropIfExists('gift_cards');
    }
}
