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
       Schema::table('tarefas', function (Blueprint $table) {
       // $table->dropColumn('tipo_tarefa'); // remove o antigo campo string
        $table->foreignId('tipo_tarefa_id')->nullable()->constrained('tipo_tarefas')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tarefas', function (Blueprint $table) {
        $table->dropForeign(['tipo_tarefa_id']);
        $table->dropColumn('tipo_tarefa_id');
     //   $table->string('tipo_tarefa');
    });
    }
};
