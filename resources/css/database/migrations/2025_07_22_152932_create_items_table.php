<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations..
     */
    public function up(): void
    {
       Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quarto_id')->constrained('quartos')->onDelete('cascade');
            $table->string('nome');
            $table->string('classificacao')->nullable();
            $table->string('marca')->nullable();
            $table->decimal('preco', 8, 2)->default(0);
            $table->integer('quantidade')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itens');
    }
};
