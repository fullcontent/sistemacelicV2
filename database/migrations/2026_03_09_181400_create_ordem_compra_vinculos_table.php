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
        Schema::create('ordem_compra_vinculos', function (Blueprint $table) {
            $table->integer('ordemCompra_id');
            $table->integer('servico_id');
            $table->decimal('valor', 10)->nullable();
            $table->string('reembolso', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordem_compra_vinculos');
    }
};
