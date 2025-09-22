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
        Schema::create('publication_files', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->nullable();
            $table->foreignId('publication_id')
                    ->constrained('publications')
                    ->onDelete('cascade');

            $table->string('name');    // Nom du fichier original
            $table->string('path');        // Chemin de stockage
            $table->string('mime_type')->nullable(); // Type MIME
            $table->bigInteger('size')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publication_files');
    }
};
