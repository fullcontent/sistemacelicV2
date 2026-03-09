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
        Schema::create('servico_financeiros', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('servico_id');
            $table->double('valorTotal');
            $table->double('valorFaturado')->nullable();
            $table->double('valorFaturar')->nullable();
            $table->double('valorAberto')->nullable();
            $table->string('status')->default('aberto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servico_financeiros');
    }
};
