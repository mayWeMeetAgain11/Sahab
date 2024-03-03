<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->date('starting_date');
            $table->date('ending_date');
            $table->double('total_price');
            $table->double('address');
            $table->enum('payment_method', ['master card']);
            $table->enum('status', ['placed', 'completed', 'canceled'])->default('placed');
            $table->string('transaction_id');
            $table->string('invoice_reference');
            $table->timestamps();
            $table->foreignId('place_id')->nullable()->constrained('places');
            $table->foreignId('service_id')->nullable()->constrained('services');
            $table->foreignId('user_id')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
