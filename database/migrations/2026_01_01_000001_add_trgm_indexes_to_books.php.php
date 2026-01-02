<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm');

        DB::statement('
            CREATE INDEX books_title_trgm_idx
            ON books
            USING GIN (title gin_trgm_ops)
        ');

        DB::statement('
            CREATE INDEX books_description_trgm_idx
            ON books
            USING GIN (description gin_trgm_ops)
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS books_title_trgm_idx');
        DB::statement('DROP INDEX IF EXISTS books_description_trgm_idx');
    }
};
