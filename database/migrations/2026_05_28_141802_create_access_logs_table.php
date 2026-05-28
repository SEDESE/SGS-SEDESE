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
    Schema::create('access_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
        $table->enum('evento', ['login', 'logout']);
        $table->string('ip', 45);
        $table->timestamp('created_at')->useCurrent();
        // sem updated_at — logs são imutáveis

        $table->index('user_id');
        $table->index('created_at');
    });
    }
};
