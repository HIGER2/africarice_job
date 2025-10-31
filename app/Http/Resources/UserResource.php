<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\File;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            // 'id'         => $this->id,
            'uuid'       => $this->uuid,
            'name'       => $this->name,
            'last_name'  => $this->last_name,
            'country_code'       => $this->country_code,
            'phone'      => $this->phone,
            'phone_code'      => $this->country_code . $this->phone,
            'email'      => $this->email,
            'role'       => $this->role,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,

            'application' => $this->whenLoaded('application') ? [
                // 'id'         => $this->application->id,
                'uuid'       => optional($this->application)->uuid,
                // 'created_at' => $this->application->created_at,
                // 'updated_at' => $this->application->updated_at,

                'origin' => $this->application->origin ? [
                    // 'id'               => $this->application->origin->id,
                    'uuid'             => $this->application->origin->uuid,
                    'nationality'      => $this->application->origin->nationality,
                    'second_nationality'      => $this->application->origin->second_nationality,
                    'country'          => $this->application->origin->country,
                    'city'             => $this->application->origin->city,
                    'experience_years' => $this->application->origin->experience_years,
                    'french_level'     => $this->application->origin->french_level,
                    'english_level'    => $this->application->origin->english_level,
                ] : null,

                'documents' => $this->application->documents->map(function ($doc) {
                    return [
                        // 'id'   => $doc->id,
                        'uuid' => $doc->uuid,
                        'name' => 'cv_' . $this->name . '_' . $this->last_name . '_' . $this->email . '.' . File::extension($doc->name),
                        'path' => $doc->path,
                    ];
                }),

                'diplomas' => $this->application->diplomas->map(function ($d) {
                    return [
                        // 'id'     => $d->id,
                        'uuid'   => $d->uuid,
                        'diploma' => $d->diploma,
                        'option' => $d->option,
                    ];
                }),

                'cgiar_information' => $this->application->cgiarInformation ? [
                    // 'id'           => $this->application->cgiarInformation->id,
                    'uuid'         => $this->application->cgiarInformation->uuid,
                    'current'      => $this->application->cgiarInformation->current,
                    'cgiar_center' => $this->application->cgiarInformation->cgiar_center,
                    'cgiar_email'  => $this->application->cgiarInformation->cgiar_email,
                ] : null,

                'experiences' => $this->application->experiences->map(function ($e) {
                    return [
                        // 'id'           => $e->id,
                        'uuid'         => $e->uuid,
                        'company_name' => $e->company_name,
                        'position'     => $e->position,
                        'start_date'   => $e->start_date,
                        'end_date'     => $e->end_date,
                        'current'      => $e->current,
                    ];
                }),

                'references' => $this->application->references->map(function ($r) {
                    return [
                        'uuid'       => $r->uuid,
                        'full_name'      => $r->full_name,
                        'phone'   => $r->phone,
                        'email'   => $r->email,
                        'company'   => $r->company,
                        'function'   => $r->function,
                    ];
                }),

                'identification' => $this->application->identification ? [
                    // 'id'         => $this->application->identification->id,
                    'uuid'       => $this->application->identification->uuid,
                    'birth_date' => $this->application->identification->birth_date,
                    'address'    => $this->application->identification->address,
                    'gender'     => $this->application->identification->gender,
                ] : null,
            ] : null,
        ];
    }
}
