<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGuestFieldsToReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Make user_id nullable for guest reservations
            $table->foreignId('user_id')->nullable()->change();
            
            // Add guest contact fields
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();
            $table->string('guest_organization')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Remove guest fields
            $table->dropColumn(['guest_name', 'guest_email', 'guest_phone', 'guest_organization']);
            
            // Make user_id not nullable again
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
}
