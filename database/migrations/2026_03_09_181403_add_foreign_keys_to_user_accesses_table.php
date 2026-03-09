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
        Schema::table('user_accesses', function (Blueprint $table) {
            $table->foreign(['empresa_id'])->references(['id'])->on('empresas')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['unidade_id'])->references(['id'])->on('unidades')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_accesses', function (Blueprint $table) {
            $table->dropForeign('user_accesses_empresa_id_foreign');
            $table->dropForeign('user_accesses_unidade_id_foreign');
            $table->dropForeign('user_accesses_user_id_foreign');
        });
    }
};
