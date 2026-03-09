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
        Schema::table('reembolso_taxas', function (Blueprint $table) {
            $table->foreign(['reembolso_id'])->references(['id'])->on('reembolsos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['taxa_id'])->references(['id'])->on('taxas')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reembolso_taxas', function (Blueprint $table) {
            $table->dropForeign('reembolso_taxas_reembolso_id_foreign');
            $table->dropForeign('reembolso_taxas_taxa_id_foreign');
        });
    }
};
