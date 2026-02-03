<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Make user_id nullable to allow guest orders
            if (Schema::hasColumn('orders', 'user_id')) {
                // Use raw SQL to avoid requiring doctrine/dbal
                try {
                    DB::statement('ALTER TABLE orders MODIFY user_id BIGINT UNSIGNED NULL');
                } catch (\Throwable $e) {
                    // Fallback to change() if DBAL is present
                    try { $table->unsignedBigInteger('user_id')->nullable()->change(); } catch (\Throwable $e2) { /* ignore */ }
                }
            }

            // Guest info fields
            if (!Schema::hasColumn('orders', 'guest_name')) {
                $table->string('guest_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('orders', 'guest_email')) {
                $table->string('guest_email')->nullable()->after('guest_name');
            }
            if (!Schema::hasColumn('orders', 'guest_phone')) {
                $table->string('guest_phone')->nullable()->after('guest_email');
            }

            // Optional fields used by code
            if (!Schema::hasColumn('orders', 'phone')) {
                $table->string('phone')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('orders', 'fulfillment_method')) {
                $table->string('fulfillment_method')->nullable()->after('phone');
            }
            if (Schema::hasColumn('orders', 'delivery_address')) {
                $table->text('delivery_address')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert guest fields
            if (Schema::hasColumn('orders', 'guest_name')) {
                $table->dropColumn('guest_name');
            }
            if (Schema::hasColumn('orders', 'guest_email')) {
                $table->dropColumn('guest_email');
            }
            if (Schema::hasColumn('orders', 'guest_phone')) {
                $table->dropColumn('guest_phone');
            }
            // Note: We won't force user_id back to not nullable to avoid data loss
        });
    }
};
