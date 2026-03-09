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
        Schema::create('taxas', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('servico_id');
            $table->string('nome');
            $table->double('valor');
            $table->string('boleto')->nullable();
            $table->string('comprovante')->nullable();
            $table->text('observacoes')->nullable();
            $table->date('emissao');
            $table->date('vencimento');
            $table->date('pagamento')->nullable();
            $table->string('reembolso', 50)->nullable();
            $table->string('situacao');
            $table->string('responsavelPgto', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxas');
    }
};
