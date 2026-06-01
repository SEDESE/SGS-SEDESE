<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alteracoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('aplicacao_id')
                  ->nullable()
                  ->constrained('aplicacoes')
                  ->nullOnDelete();   // preserva histórico mesmo após exclusão da aplicação
            $table->text('descricao');
            $table->timestamps();

            $table->index('user_id');
            $table->index('aplicacao_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alteracoes');
    }
};
