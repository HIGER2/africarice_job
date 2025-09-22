<?php

namespace App\Http\Resources;

use App\Models\PublicationFile;
use App\Models\Recrutement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OffreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            // 'id' => $this->id,
            'uuid' => $this->uuid,
            'reference' => $this->reference,
            'type' => $this->type,
            'recrutement_id' => $this->recrutement_id,
            'is_published' => $this->is_published ? 'Publiée' : 'Non Publiée',
            'is_closed' => $this->is_closed ? 'Cloturée' : 'Ouverte',
            'published_at' => Carbon::parse($this->published_at)->format('d/m/Y'),
            'expires_at' => Carbon::parse($this->expires_at)->format('d/m/Y'),
            'files' => $this->files ? $this->files->map(function($file){
                return [
                    'filename' => $file->filename,
                    'path' => $file->path,
                ];
            }) : null,
            'files' => $this->files ? $this->files->select('filename','path') : null,
            'candidates_count' => $this->candidatures ? $this->candidatures->count() : 0,
        ];
    }
}
