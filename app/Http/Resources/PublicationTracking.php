<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicationTracking extends JsonResource
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
            'trakings' => $this->whenLoaded('trakings', function () {
                return $this->trakings->map(function ($tracking) {
                    return [
                        'id'      => $tracking->id,
                        'status'  => $tracking->status,
                        'comment' => $tracking->comment,
                        'date'    => \Carbon\Carbon::parse($tracking->date)->format('d/m/Y'),
                    ];
                });
            }),


        ];
    }
}
