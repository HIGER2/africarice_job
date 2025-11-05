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
                // SLA / Guidelines
                '0_handled_by_hosted_center',

                // 1/9 - Recruitment form validation
                '1_recruitment_form_validation',

                // 2/9 - Job announcement process
                '2_job_announcement_process',
                '2a_translation_ongoing',
                '2b_translation_received',
                '2c_advertising',
                '2d_selection_criteria_request',
                '2e_selection_criteria_transmission',

                // 3/9 - Longlist process
                '3_longlist_process',
                '3a_application_downloading',
                '3b_longlist_matrix',

                // 4/9 - Shortlist process
                '4_shortlist_process',
                '4a_shortlist_matrix_ongoing',
                '4b_shortlist_matrix_consolidation',
                '4c_shortlist_matrix_approval',

                // 5/9 - Interview process
                '5_interview_process',
                '5a_interview_date_plan',
                '5b_rating_sheet_transmission',

                // 6/9 - Recruitment report
                '6_recruitment_report',
                '6a_report_to_send',
                '6b_report_sent',
                '6c_report_signature_process',
                '6d_report_approved_process',

                // 7/9 - Offer process
                '7_offer_process',
                '7a_offer_drafted',
                '7b_offer_sent',
                '7c_offer_negotiation',
                '7d_offer_accepted',
                '7e_offer_rejected',

                // 8/9 - Reference check
                '8_reference_check',
                '8a_reference_details_acknowledge',
                '8b_emails_to_send',
                '8c_references_to_receive',

                // 9/9 - Contract drafting
                '9_contract_drafting',
                '9a_tors_signature',
                '9b_contract_to_send',
                '9c_contract_sent',
                '9d_countersigned_contract_to_send',
                '9e_process_finalized'
            ])->default('0_handled_by_hosted_center');

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
