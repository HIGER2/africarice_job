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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->nullable();
            $table->string('reference')->unique()->nullable();
            $table->enum('type', ['internal', 'public'])->default('public');

            $table->foreignId('recrutement_id')->nullable()
                ->constrained('recrutements')
                ->onDelete('set null'); // supprime la publication si le job est supprimé

            $table->foreignId('published_by')->nullable()
                ->constrained('users') // ou 'staff' si tu as une table staff
                ->onDelete('set null');

            // $table->boolean('is_published')->default(false);
            // $table->boolean('is_closed')->default(false);
            $table->enum('status', [
                'pending',
                'open',
                'on_hold',
                'cancelled',
                'closed',
                'archived',
            ])->default('pending');

            $table->date('published_at')->nullable();
            $table->date('expires_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
