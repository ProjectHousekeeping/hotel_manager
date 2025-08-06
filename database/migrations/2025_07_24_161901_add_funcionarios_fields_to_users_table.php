<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Adiciona campos que eram do Funcionario
            $table->string('cpf')->unique()->nullable()->after('email');
            $table->string('telefone')->nullable()->after('cpf');
            $table->enum('situacao', ['disponivel', 'ocupado', 'ferias', 'afastado'])
                ->default('disponivel')->after('telefone');
            $table->foreignId('cargo_id')->nullable()->constrained('cargos')->after('situacao');
            $table->foreignId('gerente_id')->nullable()->constrained('users')->onDelete('set null')->after('cargo_id');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['cargo_id']);
            $table->dropForeign(['gerente_id']);
            $table->dropColumn(['cpf', 'telefone', 'situacao', 'cargo_id', 'gerente_id', 'deleted_at']);
        });
    }
};
