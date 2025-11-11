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
        Schema::create('publication_applications', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->nullable();
            $table->string('reference')->unique()->nullable();
            $table->enum('application_type', ['normal', 'spontaneous'])->default('normal');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // candidat
            $table->foreignId('publication_id')->nullable()->constrained()->onDelete('set null'); // publication liée
            $table->string('status')->default('pending');
            $table->date('date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publication_applications');
    }
};
