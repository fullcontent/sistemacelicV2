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
        Schema::create('propostas', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('proposta')->nullable();
            $table->string('status')->nullable();
            $table->string('faturamento')->nullable();
            $table->unsignedBigInteger('responsavel_id')->nullable();
            $table->integer('unidade_id')->nullable();
            $table->integer('empresa_id')->nullable();
            $table->double('valorTotal')->nullable();
            $table->string('solicitante')->nullable();
            $table->text('documentos')->nullable();
            $table->text('condicoesGerais')->nullable();
            $table->text('condicoesPagamento')->nullable();
            $table->text('dadosPagamento')->nullable();
            $table->integer('dadosCastro_id')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propostas');
    }
};
