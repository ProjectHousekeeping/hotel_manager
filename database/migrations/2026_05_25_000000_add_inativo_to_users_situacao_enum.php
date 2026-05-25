<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN situacao ENUM('disponivel', 'ocupado', 'ferias', 'afastado', 'inativo') NOT NULL DEFAULT 'disponivel'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN situacao ENUM('disponivel', 'ocupado', 'ferias', 'afastado') NOT NULL DEFAULT 'disponivel'");
    }
};
