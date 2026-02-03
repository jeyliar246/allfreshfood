<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vendor_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('views')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('orders_count')->default(0);
            $table->decimal('orders_total', 12, 2)->default(0);
            $table->timestamps();

            $table->unique(['vendor_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_analytics');
    }
};
