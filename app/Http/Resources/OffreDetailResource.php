<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class OffreDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'reference' => $this->reference,
            'type' => $this->type,
            'recrutement_id' => $this->recrutement_id,
            'published_by' => $this->published_by,
            'is_published' => $this->is_published,
            'is_closed' => $this->is_closed,
            'published_at' => $this->published_at,
            'expires_at' => $this->expires_at,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,

            // Relation vers le job
            'job' => $this->whenLoaded('job', function () {
                return [
                    'uuid'           => $this->job->uuid,
                    'code_recrutement' => $this->job->code_recrutement,
                    'position_title' => $this->job->position_title,
                    'center'         => $this->job->center,
                    'country_duty_station' => $this->job->country_duty_station,
                    'city_duty_station'    => $this->job->city_duty_station,
                    'grade'          => $this->job->grade,
                    'salary_post'    => $this->job->salary_post,
                ];
            }),
            'files' => collect($this->files)->map(function ($file) {
                return [
                    'name' => $file['name'] ?? null,
                    'url'  => isset($file['path']) ? $file['path'] : null,
                ];
            }),
            // Relation vers les candidatures
            // 'candidat' => $this->whenLoaded('candidatures'),
            'candidat' => $this->whenLoaded('candidatures', function () {
                // return$this->candidatures->pluck('user');
                return CanditureUser::collection($this->candidatures->pluck('user'));
            }),
        ];
    }
}
