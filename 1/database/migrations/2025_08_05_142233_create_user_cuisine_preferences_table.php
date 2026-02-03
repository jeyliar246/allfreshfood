<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_cuisine_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cuisine_id')->constrained()->onDelete('cascade');
            $table->integer('preference_level')->default(1);
            $table->timestamps();

            $table->unique(['user_id', 'cuisine_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_cuisine_preferences');
    }
};
