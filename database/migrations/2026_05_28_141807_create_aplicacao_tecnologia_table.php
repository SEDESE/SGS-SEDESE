<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aplicacao_tecnologia', function (Blueprint $table) {
            $table->foreignId('aplicacao_id')
                  ->constrained('aplicacoes')
                  ->cascadeOnDelete();
            $table->foreignId('tecnologia_id')
                  ->constrained('tecnologias')
                  ->cascadeOnDelete();
            $table->primary(['aplicacao_id', 'tecnologia_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aplicacao_tecnologia');
    }
};
