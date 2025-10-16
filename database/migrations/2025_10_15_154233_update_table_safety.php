<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 🔹 Liste des tables et colonnes à vérifier / ajouter
        $tables = [

            'references' => [
                'company' => fn(Blueprint $table) => $table->string('company')->nullable(),
            ],
            'origins' => [
                'second_nationality' => fn(Blueprint $table) => $table->string('second_nationality')->nullable(),
            ],

        ];

        // 🔹 Parcours de toutes les tables
        foreach ($tables as $tableName => $columns) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName, $columns) {
                    foreach ($columns as $column => $definition) {
                        if (!Schema::hasColumn($tableName, $column)) {
                            $definition($table); // Exécute la définition de colonne
                        }
                    }
                });
            } else {
                // (Optionnel) Créer la table si elle n'existe pas
                Schema::create($tableName, function (Blueprint $table) use ($columns) {
                    $table->id();
                    foreach ($columns as $definition) {
                        $definition($table);
                    }
                    $table->timestamps();
                });
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'users' => ['phone', 'address', 'status'],
            'publications' => ['views_count', 'is_published'],
            'recrutements' => ['country_duty_station', 'position_title'],
        ];

        foreach ($tables as $tableName => $columns) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName, $columns) {
                    foreach ($columns as $column) {
                        if (Schema::hasColumn($tableName, $column)) {
                            $table->dropColumn($column);
                        }
                    }
                });
            }
        }
    }
};
