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
        Schema::table('faturamento_servicos', function (Blueprint $table) {
            $table->foreign(['faturamento_id'])->references(['id'])->on('faturamentos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['servico_id'])->references(['id'])->on('servicos')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faturamento_servicos', function (Blueprint $table) {
            $table->dropForeign('faturamento_servicos_faturamento_id_foreign');
            $table->dropForeign('faturamento_servicos_servico_id_foreign');
        });
    }
};
