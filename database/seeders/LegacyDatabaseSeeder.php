<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LegacyDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $legacyDb = env('LEGACY_DB_DATABASE', 'sistemacelic_local');

        $tables = [
            'users',
            'unidades',
            'empresas',
            'prestadors',
            'servicos',
            'arquivos',
            'dados_castros',
            'faturamento_servicos',
            'faturamentos',
            'historicos',
            'ordem_compras',
            'ordem_compra_pagamentos',
            'ordem_compra_vinculos',
            'pendencias',
            'pendencias_vinculos',
            'prestador_comentarios',
            'propostas',
            'proposta_servicos',
            'reembolsos',
            'reembolso_taxas',
            'servico_finalizados',
            'servico_financeiros',
            'servico_lpus',
            'solicitantes',
            'solicitante_empresas',
            'taxas',
            'user_accesses',
        ];

        Schema::disableForeignKeyConstraints();

        foreach ($tables as $table) {
            $this->command->info("Migrating table: {$table}");
            try {
                // Get destination columns
                $destColumns = Schema::getColumnListing($table);
                // Get source columns
                $sourceColumns = DB::connection('mysql')->select("SHOW COLUMNS FROM {$legacyDb}.{$table}");
                $sourceColumns = array_column(json_decode(json_encode($sourceColumns), true), 'Field');

                // Intersect valid columns that exist in both
                $validColumns = array_intersect($destColumns, $sourceColumns);

                if (empty($validColumns)) {
                    $this->command->warn("No matching columns for table: {$table}");
                    continue;
                }

                $columnsStr = implode(', ', array_map(function ($col) {
                    return "`$col`"; }, $validColumns));

                // Copia do banco de dados antigo temporário pra a estrutura nova e moderna do banco.
                DB::statement("INSERT IGNORE INTO `$table` ($columnsStr) SELECT $columnsStr FROM {$legacyDb}.`{$table}`");
            } catch (\Exception $e) {
                $this->command->error("Failed to seed table {$table}: " . $e->getMessage());
            }
        }

        Schema::enableForeignKeyConstraints();

        $this->command->info('Seed das tabelas de negócio do antigo sistemacelic finalizado! 🌱');
    }
}
