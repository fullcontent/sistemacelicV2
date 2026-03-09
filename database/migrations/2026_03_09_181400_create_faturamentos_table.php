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
        Schema::create('faturamentos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('empresa_id');
            $table->string('nome')->nullable();
            $table->double('valorTotal')->nullable();
            $table->string('nf')->nullable();
            $table->string('obs')->nullable();
            $table->date('dataPagamento')->nullable();
            $table->string('link', 50)->nullable();
            $table->integer('dadosCastro_id')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturamentos');
    }
};
