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
        Schema::create('traking_publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publication_id')->constrained()->onDelete('cascade'); // publication liée
            $table->enum('status', [
                // L’offre vient d’être créée et n’a pas encore été traitée
                'pending',

                // L’offre est en cours d’examen par le manager ou le RH
                'in_review',

                // Des candidats ont été présélectionnés pour l’offre
                'shortlisted',

                // Entretiens programmés pour un ou plusieurs candidats
                'interview_scheduled',

                // Entretiens terminés
                'interview_completed',

                // Une ou plusieurs offres envoyées aux candidats
                'offer_sent',

                // Au moins un candidat a accepté l’offre
                'offer_accepted',

                // Tous les postes pourvus ou l’offre est terminée
                'closed',

                // Pour l’archivage et l’historique des offres terminées
                'archived',
            ])->default('pending');
            $table->text('comment')->nullable();
            $table->date('date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traking_publications');
    }
};
