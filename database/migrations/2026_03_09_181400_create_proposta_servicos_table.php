<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proposta_servicos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('proposta_id');
            $table->text('servico')->nullable();
            $table->text('escopo')->nullable();
            $table->double('valor')->nullable();
            $table->integer('posicao')->nullable();
            $table->integer('servicoPrincipal')->nullable();
            $table->integer('servicoLpu_id')->nullable();
            $table->unsignedBigInteger('responsavel_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposta_servicos');
    }
};
