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
        Schema::create('cargo_tipo_tarefa', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cargo_id')->constrained()->onDelete('cascade');

            $table->foreignId('tipo_tarefa_id')->constrained('tipo_tarefas')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo_tipo_tarefa');
    }
};
