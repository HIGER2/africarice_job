<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PublicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


    public function toArray(Request $request): array
    {
        return [
            // 'id' => $this->id,
            'uuid' => $this->uuid,
            'reference' => $this->reference,
            'type' => $this->type,
            'is_published' => $this->is_published,
            'is_closed' => $this->is_closed,
            'published_at' => $this->published_at
                ? Carbon::parse($this->published_at)
                ->timezone('Africa/Abidjan')
                ->format('Y-m-d H:i:s')
                : null,

            'expires_at' => $this->expires_at
                ? Carbon::parse($this->expires_at)
                ->timezone('Africa/Abidjan')
                ->format('Y-m-d H:i:s')
                : null,
            'job' => [
                // 'id' => $this->job->id,
                // 'code_recrutement' => $this->job->code_recrutement,
                'position_title' => $this?->job?->position_title,
                'center' => $this?->job?->center,
                'country_duty_station' => $this?->job?->country_duty_station,
                'city_duty_station' => $this?->job?->city_duty_station,
                'grade' => $this?->job?->grade,
                'division' => $this?->job?->division,
                // ajoute d'autres champs si besoin
            ],
            'files' => collect($this->files)->map(function ($file) {
                return [
                    'name' => $file['name'] ?? null,
                    'url'  => isset($file['path']) ? $file['path'] : null,
                ];
            }),
        ];
    }
}
