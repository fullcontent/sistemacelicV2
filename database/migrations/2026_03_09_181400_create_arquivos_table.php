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
        Schema::create('arquivos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('empresa_id')->nullable();
            $table->integer('unidade_id')->nullable();
            $table->integer('servico_id')->nullable();
            $table->string('arquivo')->nullable();
            $table->string('nome');
            $table->integer('pendencia_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arquivos');
    }
};
