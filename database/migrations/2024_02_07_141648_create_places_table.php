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
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image');
            $table->string('address');
            $table->string('description');
            $table->boolean('featured')->default(false);
            $table->boolean('available')->default(true);
            $table->boolean('bookable')->default(true);
            $table->double('weekday_price');
            $table->double('weekend_price');
            $table->enum('tag', ['girls', 'family', 'all']);
            $table->timestamps();
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('vendor_id')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
