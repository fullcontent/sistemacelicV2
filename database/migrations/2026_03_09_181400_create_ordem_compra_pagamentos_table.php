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
        Schema::create('ordem_compra_pagamentos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('ordemCompra_id')->nullable();
            $table->decimal('valor', 10)->nullable();
            $table->date('dataPagamento')->nullable();
            $table->date('dataVencimento')->nullable();
            $table->string('formaPagamento')->nullable();
            $table->integer('parcela')->nullable();
            $table->string('comprovante')->nullable();
            $table->text('obs')->nullable();
            $table->string('situacao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordem_compra_pagamentos');
    }
};
