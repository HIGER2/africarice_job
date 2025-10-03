<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CanditureUser extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $this->application->diplomas->map(function ($diploma, $index) use (&$flattened) {
            $num = $index + 1;
            $flattened["diploma{$num}"] = $diploma->diploma ?? 'n/a';
            // $flattened["option{$num}"] = $diploma->option;
        });
        $this->application->references->map(function ($reference, $index) use (&$flattened) {
            $num = $index + 1;
            $flattened["reference{$num}"] = "Nom : " . $reference->full_name . '; function: ' . $reference->function . '; phone: ' . $reference->phone . '; email: ' . $reference->email;
            // $flattened["option{$num}"] = $diploma->option;
        });

        $this->application->experiences->map(function ($experience, $index) use (&$flattened) {
            $num = $index + 1;
            $flattened["experience{$num}"] = "Entreprise{$num} : " . $experience->company_name . '; Poste: ' . $experience->position . '; De: ' . $experience->start_date . '; A: ' . $experience->end_date;
            // $flattened["option{$num}"] = $diploma->option;
        });


        return [
            // 'candidature_id' => $this->id,
            'uuid' => $this->uuid,
            // 'status' => $this->status,
            // 'date' => $this->date,

            // Publication / Job
            // 'publication_id' => $this->publication->id,
            // 'publication_reference' => $this->publication->reference,
            // 'publication_type' => $this->publication->type,
            // 'publication_is_published' => $this->publication->is_published,
            // 'publication_is_closed' => $this->publication->is_closed ? 'Cloturée' : 'Ouverte',
            // 'job_id' => $this->publication->job->id,
            // 'job_position_title' => $this->publication->job->position_title,
            // 'job_center' => $this->publication->job->center,
            // 'job_city' => $this->publication->job->city_duty_station,
            // 'job_country' => $this->publication->job->country_duty_station,
            // 'job_grade' => $this->publication->job->grade,
            // 'job_contract_from' => $this->publication->job->contract_from,
            // 'job_contract_to' => $this->publication->job->contract_to,
            // 'job_salary_post' => $this->publication->job->salary_post,

            // User
            // 'user_id' => $this->id,
            'candidat' => $this->name . ' ' . $this->last_name,
            'user_email' => $this->email,
            'user_phone' => $this->phone,

            // Application
            // 'application_id' => $this->user->application->id,
            // 'application_uuid' => $this->user->application->uuid,

            // Origin
            // 'origin_id' => $this->application->origin->id,
            'origin_nationality' => $this->application->origin->nationality,
            'origin_country' => $this->application->origin->country,
            'origin_city' => $this->application->origin->city,
            'origin_experience_years' => $this->application->origin->experience_years,
            'origin_french_level' => $this->application->origin->french_level,
            'origin_english_level' => $this->application->origin->english_level,
            // Diplomas
            // Diplomas aplatis

            // 'diplomas' => $this->user->application->diplomas ? $this->user->application->diplomas->map(function($diploma){
            //     return [
            //         'diploma' => $diploma->diploma,
            //         'option' => $diploma->option,
            //     ];
            // }) : null,
            // Documents
            // 'document_id' => $this->user->application->documents->id,
            'documents' => $this->application->documents->map(function ($document, $index) use (&$flattened) {
                return (object)[
                    'name' => $document['name'],
                    'path' => $document['path'],
                ];
            }),

            // 'document_path' => $this->user->application->documents->path,
        ] + ($flattened ?? []);
        // $this->application->diplomas->map(function ($diploma, $index) use (&$flattened) {
        //         $num = $index + 1;
        //         $flattened["diploma{$num}"] = $diploma->diploma;
        //         // $flattened["option{$num}"] = $diploma->option;
        //     });
        //     $this->application->experiences->map(function ($experience, $index) use (&$flattened) {
        //         $num = $index + 1;
        //         $flattened["experience{$num}"] ="Entreprise{$num} : ".$experience->company_name.'; Poste: '.$experience->position.'; De: '.$experience->start_date.'; A: '.$experience->end_date;
        //         // $flattened["option{$num}"] = $diploma->option;
        //     });

        //     return [

        //         // "data"=>$this->resource,
        //     // 'candidature_id' => $this->id,
        //     // // 'candidature_uuid' => $this->uuid,
        //     // 'status' => $this->status,
        //     // 'date' => $this->date,

        //     // Publication / Job
        //     // 'publication_id' => $this->publication->id,
        //     // 'publication_reference' => $this->publication->reference,
        //     // 'publication_type' => $this->publication->type,
        //     // 'publication_is_published' => $this->publication->is_published,
        //     // 'publication_is_closed' => $this->publication->is_closed ? 'Cloturée' : 'Ouverte',
        //     // // 'job_id' => $this->publication->job->id,
        //     // 'job_position_title' => $this->publication->job->position_title,
        //     // 'job_center' => $this->publication->job->center,
        //     // 'job_city' => $this->publication->job->city_duty_station,
        //     // 'job_country' => $this->publication->job->country_duty_station,
        //     // 'job_grade' => $this->publication->job->grade,
        //     // 'job_contract_from' => $this->publication->job->contract_from,
        //     // 'job_contract_to' => $this->publication->job->contract_to,
        //     // 'job_salary_post' => $this->publication->job->salary_post,

        //     // User
        //     // 'user_id' => $this->user->id,
        //     // 'candidat' => $this->name.' '.$this->user->last_name,
        //     // 'user_email' => $this->email,
        //     // 'user_phone' => $this->phone,

        //     // // Application
        //     // 'application_id' => $this->application->id,
        //     // 'application_uuid' => $this->application->uuid,

        //     // // Origin
        //     // 'origin_id' => $this->application->origin->id,
        //     // 'origin_nationality' => $this->application->origin->nationality,
        //     // 'origin_country' => $this->application->origin->country,
        //     // 'origin_city' => $this->application->origin->city,
        //     // 'origin_experience_years' => $this->application->origin->experience_years,
        //     // 'origin_french_level' => $this->application->origin->french_level,
        //     // 'origin_english_level' => $this->application->origin->english_level,
        //     // // Diplomas
        //     //  // Diplomas aplatis
        //     // // 'diplomas' => $this->user->application->diplomas ? $this->user->application->diplomas->map(function($diploma){
        //     // //     return [
        //     // //         'diploma' => $diploma->diploma,
        //     // //         'option' => $diploma->option,
        //     // //     ];
        //     // // }) : null,
        //     // // Documents
        //     // // 'document_id' => $this->user->application->documents->id,
        //     // 'documents' => [
        //     //     (object)[
        //     //     'name'=> $this->application->documents[0]['name'],
        //     //     'path'=> $this->application->documents[0]['path'],
        //     //     ],
        //     //     (object)[
        //     //     'name'=> $this->application->documents[1]['name'],
        //     //     'path'=> $this->application->documents[1]['path'],
        //     //     ],
        //     // ],
        //     // 'document_path' => $this->user->application->documents->path,
        // ]
        // //  + ($flattened ?? [] )
        //  ;
    }
}
