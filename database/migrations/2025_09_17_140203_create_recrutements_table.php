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
        Schema::create('recrutements', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->nullable();
            $table->string('code_recrutement')->nullable()->unique();
            $table->string('city_duty_station')->nullable();
            $table->date('contract_from')->nullable();
            $table->date('contract_to')->nullable();
            $table->date('date')->nullable();
            $table->string('grade')->nullable();
            $table->string('division')->nullable();
            $table->string('program')->nullable();
            $table->string('sub_unit')->nullable();
            $table->string('education_level')->nullable();
            $table->string('resources_type')->nullable();
            $table->enum('contract_time', ['full_time', 'part_time'])->nullable();
            $table->enum('is_active', ['active', 'inactive'])->default('active');
            $table->string('supervisor_name')->nullable();
            $table->string('supervisor_position')->nullable();
            $table->string('recruitment_type')->nullable();
            $table->string('country_of_recruitment')->nullable();
            $table->string('cgiar_workforce_group')->nullable();
            $table->string('cgiar_group')->nullable();
            $table->enum('cgiar_appointed', ['yes', 'no'])->default('yes');
            $table->decimal('percent_time_other_center', 10, 2)->nullable(); // Ex: 25.00
            $table->enum('shared_working_arrangement', ['yes', 'no'])->default('yes');
            $table->string('initiative_lead')->nullable();
            $table->string('initiative_personnel')->nullable();
            $table->decimal('salary_post', 15, 2)->nullable();

            $table->string('reference')->nullable()->unique();
            $table->string('position_title')->nullable();
            $table->string('country_duty_station')->nullable();
            $table->string('center')->nullable(); // AfricaRice/Hosted
            $table->string('manager')->nullable();
            $table->enum('reason', ['new', 'replacement'])->default('new');
            $table->string('reason_replacement')->nullable();
            $table->foreignId('assign_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recrutements');
    }
};
