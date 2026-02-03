<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('platform_analytics', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('new_users')->default(0);
            $table->integer('new_vendors')->default(0);
            $table->integer('orders_count')->default(0);
            $table->decimal('orders_total', 12, 2)->default(0);
            $table->decimal('commission_total', 12, 2)->default(0);
            $table->timestamps();

            $table->unique(['date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_analytics');
    }
};
