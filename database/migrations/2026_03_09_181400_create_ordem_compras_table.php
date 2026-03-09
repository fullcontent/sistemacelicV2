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
        Schema::create('ordem_compras', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('prestador_id')->nullable();
            $table->integer('servico_id')->nullable();
            $table->decimal('valorServico', 10)->nullable();
            $table->string('situacao')->nullable();
            $table->string('formaPagamento')->nullable();
            $table->text('escopo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordem_compras');
    }
};
