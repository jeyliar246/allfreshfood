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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('category')->nullable();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 8, 2);
            $table->decimal('pprice', 8, 2)->nullable();
            $table->decimal('original_price', 8, 2)->nullable();
            $table->string('deal')->nullable()->default('inactive');
            $table->decimal('discount', 8, 2)->nullable();
            $table->integer('stock')->nullable()->default(0);
            $table->string('status')->default('active');
            $table->string('image')->nullable();
            $table->string('cuisine')->nullable();
            $table->boolean('halal')->default(false);
            $table->boolean('vegan')->default(false);
            $table->boolean('gluten_free')->default(false);
            $table->boolean('organic')->default(false);
            $table->boolean('fair_trade')->default(false);
            $table->boolean('non_GMO')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
