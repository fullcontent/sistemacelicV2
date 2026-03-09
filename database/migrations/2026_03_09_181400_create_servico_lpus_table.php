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
        Schema::create('servico_lpus', function (Blueprint $table) {
            $table->increments('id');
            $table->text('nomeCelic');
            $table->text('nome')->nullable();
            $table->string('categoria', 50)->nullable();
            $table->string('tipoServico', 50)->nullable();
            $table->string('processo', 50)->nullable();
            $table->text('escopo')->nullable();
            $table->double('valor')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servico_lpus');
    }
};
