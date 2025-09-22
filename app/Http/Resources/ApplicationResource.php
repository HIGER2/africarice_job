<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            // Champs de l'application
            // 'id'        => $this->id,
            // 'uuid'      => $this->uuid,
            'user_id'   => $this->user_id,
            // 'created_at'=> $this->created_at,
            // 'updated_at'=> $this->updated_at,
            'user'=> optional($this->user)->name .' '.optional($this->user)->name,
            'phone'=> optional($this->user)->phone,
            'email'=> optional($this->user)->email,
            'documents' => [
                (object)[
                'name'=> optional($this->documents[0])->name,
                'path'=> optional($this->documents[0])->path,
                ],
                (object)[
                'name'=> optional($this->documents[0])->name,
                'path'=> optional($this->documents[0])->path,
                ],
                ]
            // Champs de origin (aplaties)
            // 'origin_id'          => optional($this->origin)->id,
            // 'origin_uuid'        => optional($this->origin)->uuid,
            // 'nationality'        => optional($this->origin)->nationality,
            // 'country'            => optional($this->origin)->country,
            // 'city'               => optional($this->origin)->city,
            // 'experience_years'   => optional($this->origin)->experience_years.' ans',
            // 'french_level'       => optional($this->origin)->french_level,
            // 'english_level'      => optional($this->origin)->english_level,

            // // Champs de documents (aplaties)
            // 'document_id'   => optional($this->documents)->id,
            // 'document_uuid' => optional($this->documents)->uuid,
            // 'document_name' => optional($this->documents)->name,
            // 'document_path' => optional($this->documents)->path,
            // 'document_mime' => optional($this->documents)->mime_type,
            // 'document_size' => optional($this->documents)->size,
        ];
    }
}
