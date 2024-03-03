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
        Schema::create('special_days', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->double('price');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
            $table->foreignId('place_id')->constrained('places');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_days');
    }
};
