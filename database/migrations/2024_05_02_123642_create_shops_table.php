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
        // I saw quite a few different ways to store coordinates when trying to find the ideal data type.
        // For the sake of the test I will move on like this, however for a real application I would do some digging and get to the bottom of it
        // Something that did look very interesting was this: https://dev.mysql.com/doc/refman/8.0/en/spatial-types.html
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->decimal('latitude', 10, 8)->nullable(false);
            $table->decimal('longitude', 11, 8)->nullable(false);
            $table->boolean('is_open')->default(false);

            // An enum is an option here, but I personally don't like some of the behaviour
            $table->string('store_type')->nullable(false);
            $table->integer('max_delivery_meters')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
