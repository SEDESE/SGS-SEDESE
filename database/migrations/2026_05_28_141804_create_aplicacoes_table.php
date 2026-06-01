<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aplicacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('so_id')
                  ->nullable()
                  ->constrained('sistemas_operacionais')
                  ->nullOnDelete();

            $table->string('nome_aplicacao');
            $table->string('ip', 45)->nullable();
            $table->enum('ambiente', ['Producao', 'Homologacao', 'Desenvolvimento'])->nullable();
            $table->string('url')->nullable();

            $table->string('usuario_os')->nullable();
            $table->text('senha_os')->nullable();       // criptografado em repouso

            $table->string('usuario_site')->nullable();
            $table->text('senha_site')->nullable();     // criptografado em repouso

            $table->string('database')->nullable();
            $table->string('usuario_db')->nullable();
            $table->text('senha_db')->nullable();       // criptografado em repouso

            $table->string('caminho', 500)->nullable();
            $table->string('git', 500)->nullable();
            $table->string('empresa_desenvolvedor')->nullable();
            $table->string('responsavel_diretor')->nullable();

            $table->timestamps();

            $table->index('nome_aplicacao');            // RNF-04.2
            $table->index('ambiente');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aplicacoes');
    }
};
