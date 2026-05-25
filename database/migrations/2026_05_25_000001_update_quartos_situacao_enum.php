<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Mapeia valores legados para o vocabulário da spec.
        DB::table('quartos')->where('situacao', 'finalizada')->update(['situacao' => 'disponivel']);
        DB::table('quartos')->where('situacao', 'pedido_encaminhado')->update(['situacao' => 'limpeza_pendente']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE quartos MODIFY COLUMN situacao "
                . "ENUM('disponivel','ocupado','limpeza_pendente','limpeza_em_andamento','manutencao_pendente','manutencao_em_andamento','fechado') "
                . "NOT NULL DEFAULT 'disponivel'"
            );
        }
    }

    public function down(): void
    {
        DB::table('quartos')->where('situacao', 'limpeza_pendente')->update(['situacao' => 'pedido_encaminhado']);
        DB::table('quartos')->whereIn('situacao', ['limpeza_em_andamento', 'manutencao_pendente', 'manutencao_em_andamento', 'fechado'])
            ->update(['situacao' => 'disponivel']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE quartos MODIFY COLUMN situacao "
                . "ENUM('finalizada','limpeza_em_andamento','manutencao_em_andamento','pedido_encaminhado','disponivel','ocupado') "
                . "NOT NULL DEFAULT 'disponivel'"
            );
        }
    }
};
