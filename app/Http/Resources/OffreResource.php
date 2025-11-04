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
            'reference' => $this->reference ?? "N/A",
            'type' => $this->type,
            'position_title' => $this->whenLoaded('job')?->position_title ?? "N/A",
            'recrutement_id' => $this->recrutement_id,
            // 'is_published' => $this->is_published ? 'Publiée' : 'Non Publiée',
            'status' => $this->status,
            'ongoing' => $this->lastTracking?->status ?? "N/A",
            'comment' => $this->lastTracking?->comment ?? "N/A",
            'date_tracking' => $this->lastTracking?->date ?? "N/A",
            'reason' => $this->whenLoaded('job')?->reason ?? "N/A",
            'manager' => $this->whenLoaded('job')?->manager ?? "N/A",
            'center' => $this->whenLoaded('job')?->center ?? "N/A",
            'reason_replacement' => $this->whenLoaded('job')?->reason_replacement ?? "N/A",
            'country_duty_station' => $this->whenLoaded('job')?->country_duty_station ?? "N/A",
            'city_duty_station' => $this->whenLoaded('job')?->city_duty_station ?? "N/A",
            'division' => $this->whenLoaded('job')?->division ?? "N/A",
            'grade' => $this->whenLoaded('job')?->grade ?? "N/A",
            'program' => $this->whenLoaded('job')?->program ?? "N/A",
            'assign_by' => $this->whenLoaded('job', function ($relation) {
                return $relation?->assign ? $relation->assign->name . ' ' . $relation->assign->last_name : "N/A";
            }),
            // 'is_closed' => $this->is_closed ? 'Cloturée' : 'Ouverte',
            'published_at' => Carbon::parse($this->published_at)->format('d/m/Y'),
            'expires_at' => Carbon::parse($this->expires_at)->format('d/m/Y'),
            'files' => $this->files ? $this->files->map(function ($file) {
                return [
                    'filename' => $file->filename,
                    'path' => $file->path,
                ];
            }) : null,
            'files' => $this->files ? $this->files->select('filename', 'path') : null,
            'candidates_count' => $this->candidatures ? $this->candidatures->count() : 0,
        ];
    }
}
