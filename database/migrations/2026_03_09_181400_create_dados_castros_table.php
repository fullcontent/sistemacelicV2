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
        Schema::create('dados_castros', function (Blueprint $table) {
            $table->integer('id')->nullable();
            $table->text('cnpj')->nullable();
            $table->text('razaoSocial')->nullable();
            $table->text('chavePix')->nullable();
            $table->text('banco')->nullable();
            $table->text('agencia')->nullable();
            $table->text('conta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dados_castros');
    }
};
