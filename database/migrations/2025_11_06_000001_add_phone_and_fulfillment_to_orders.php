<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'phone')) {
                $table->string('phone')->nullable()->after('delivery_address');
            }
            if (!Schema::hasColumn('orders', 'fulfillment_method')) {
                $table->string('fulfillment_method')->default('delivery')->after('phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'fulfillment_method')) {
                $table->dropColumn('fulfillment_method');
            }
            if (Schema::hasColumn('orders', 'phone')) {
                $table->dropColumn('phone');
            }
        });
    }
};
