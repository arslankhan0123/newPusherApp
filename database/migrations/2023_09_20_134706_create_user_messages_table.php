<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_messages', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('message_id')->nullable()->unsigned();
            $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('sender_id')->nullable()->unsigned();
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('receiver_id')->nullable()->unsigned();
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            
            $table->tinyInteger('type')->default(1)->comment('1: group message, 2:personal message');
            $table->tinyInteger('seen_status')->default(1)->comment('1: seen');
            $table->tinyInteger('deliver_status')->default(1)->comment('1: delivered');
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
        Schema::dropIfExists('user_messages');
    }
};
