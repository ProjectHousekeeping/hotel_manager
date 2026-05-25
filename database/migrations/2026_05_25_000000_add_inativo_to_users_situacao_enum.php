<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite não impõe enum — o valor já funciona sem ALTER TABLE
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN situacao ENUM('disponivel', 'ocupado', 'ferias', 'afastado', 'inativo') NOT NULL DEFAULT 'disponivel'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN situacao ENUM('disponivel', 'ocupado', 'ferias', 'afastado') NOT NULL DEFAULT 'disponivel'");
        }
    }
};
