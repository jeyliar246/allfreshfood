<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // MySQL: drop FK, alter column to NULL, add FK back
        DB::statement('ALTER TABLE deliveries DROP FOREIGN KEY deliveries_delivery_person_id_foreign');
        DB::statement('ALTER TABLE deliveries MODIFY delivery_person_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE deliveries ADD CONSTRAINT deliveries_delivery_person_id_foreign FOREIGN KEY (delivery_person_id) REFERENCES users(id) ON DELETE CASCADE');
    }

    public function down(): void
    {
        // Revert to NOT NULL (may fail if null rows exist; ensure data integrity before rollback)
        DB::statement('ALTER TABLE deliveries DROP FOREIGN KEY deliveries_delivery_person_id_foreign');
        DB::statement('UPDATE deliveries SET delivery_person_id = 0 WHERE delivery_person_id IS NULL');
        DB::statement('ALTER TABLE deliveries MODIFY delivery_person_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE deliveries ADD CONSTRAINT deliveries_delivery_person_id_foreign FOREIGN KEY (delivery_person_id) REFERENCES users(id) ON DELETE CASCADE');
    }
};
