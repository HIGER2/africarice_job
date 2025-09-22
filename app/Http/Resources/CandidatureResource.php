<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
            return [
            'candidature_id' => $this->id,
            'candidature_uuid' => $this->uuid,
            'status' => $this->status,
            'date' => $this->date,

            // Publication / Job
            'publication_id' => $this->publication->id,
            'publication_reference' => $this->publication->reference,
            'publication_type' => $this->publication->type,
            'publication_is_published' => $this->publication->is_published,
            'publication_is_closed' => $this->publication->is_closed ? 'Cloturée' : 'Ouverte',
            'job_id' => $this->publication->job->id,
            'job_position_title' => $this->publication->job->position_title,
            'job_center' => $this->publication->job->center,
            'job_city' => $this->publication->job->city_duty_station,
            'job_country' => $this->publication->job->country_duty_station,
            'job_grade' => $this->publication->job->grade,
            'job_contract_from' => $this->publication->job->contract_from,
            'job_contract_to' => $this->publication->job->contract_to,
            'job_salary_post' => $this->publication->job->salary_post,

            // User
            'user_id' => $this->user->id,
            'candidat' => $this->user->name.' '.$this->user->last_name,
            'user_email' => $this->user->email,
            'user_phone' => $this->user->phone,

            // Application
            'application_id' => $this->user->application->id,
            'application_uuid' => $this->user->application->uuid,

            // Origin
            'origin_id' => $this->user->application->origin->id,
            'origin_nationality' => $this->user->application->origin->nationality,
            'origin_country' => $this->user->application->origin->country,
            'origin_city' => $this->user->application->origin->city,
            'origin_experience_years' => $this->user->application->origin->experience_years,
            'origin_french_level' => $this->user->application->origin->french_level,
            'origin_english_level' => $this->user->application->origin->english_level,

            // Documents
            // 'document_id' => $this->user->application->documents->id,
            'documents' => [
                (object)[
                'name'=> $this->user->application->documents[0]['name'],
                'path'=> $this->user->application->documents[0]['path'],
                ],
                (object)[
                'name'=> $this->user->application->documents[1]['name'],
                'path'=> $this->user->application->documents[1]['path'],
                ],
            ],
            // 'document_path' => $this->user->application->documents->path,
        ];
    }
}
