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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('cuisine');
            $table->text('description');
            $table->string('location');
            $table->string('postcode');
            $table->string('phone');
            $table->string('email');
            $table->string('image')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('verified')->default(false);
            $table->boolean('featured')->default(false);
            $table->decimal('min_order', 8, 2);
            $table->decimal('free_delivery_over', 8, 2);
            $table->string('opening_hours');
            $table->string('delivery_time');
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('sort_code')->nullable();
            $table->decimal('delivery_fee', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
