<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('quartos', function (Blueprint $table) {
            $table->id();
            $table->integer('numero')->unique();
            $table->string('tipo');
            $table->decimal('valor_diaria', 8, 2);
            $table->enum('situacao', [
                'finalizada',
                'limpeza_em_andamento',
                'manutencao_em_andamento',
                'pedido_encaminhado',
                'disponivel', // Adicionando outras situações comuns
                'ocupado'
            ])->default('disponivel');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quartos');
    }
};
