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

            'publication_applications' => [
                'publication_id' => [
                    'update' => function (Blueprint $table) {
                        $table->foreignId('publication_id')->nullable()->change();
                    },
                ],
                // 'application_type' => [
                //     'create' => fn(Blueprint $table) => $table->enum('application_type', ['normal', 'spontaneous'])->default('normal')
                // ],
            ],
            'references' => [
                'country_code' => [
                    'create' => fn(Blueprint $table) =>
                    $table->string('country_code')->nullable(),
                    // 'update' => fn(Blueprint $table) =>
                    // $table->enum('is_active', ['active', 'not_active'])->nullable()->change(),
                ],
            ],
            // 'origins' => [
            //     'second_nationality' => fn(Blueprint $table) => $table->string('second_nationality')->nullable(),
            // ],

        ];

        // 🔹 Parcours de toutes les tables
        foreach ($tables as $tableName => $columns) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName, $columns) {
                    foreach ($columns as $column => $actions) {
                        if (!Schema::hasColumn($tableName, $column)) {
                            $actions['create']($table);
                        } else {
                            $actions['update']($table);
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
