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
        Schema::create('prestador_comentarios', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('prestador_id');
            $table->integer('ordemCompra_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('rating');
            $table->text('comentario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestador_comentarios');
    }
};
