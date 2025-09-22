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
            Schema::create('experiences', function (Blueprint $table) {
                        $table->id();
                        $table->uuid('uuid')->unique()->nullable();
                        $table->foreignId('application_id')->constrained()->onDelete('cascade');
                        $table->string('company_name');
                        $table->string('position');
                        $table->date('start_date');
                        $table->date('end_date')->nullable();
                        $table->boolean('current')->default(false);
                        $table->timestamps();
                    });
            }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
