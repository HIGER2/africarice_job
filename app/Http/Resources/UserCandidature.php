<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCandidature extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            // Champs de l'application
            // 'id'        => $this->id,
            'uuid'      => $this->uuid,
            // 'user_id'   => $this->user_id,
            // 'created_at'=> $this->created_at,
            // 'updated_at'=> $this->updated_at,
            'user' => $this->name . ' ' . $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            // Champs de origin (aplaties)
            'nationality'      => $this->whenLoaded('application', function () {
                return optional($this->application->origin)->nationality;
            }),
            'country'          => $this->whenLoaded('application', function () {
                return optional($this->application->origin)->country;
            }),
            'city'             => $this->whenLoaded('application', function () {
                return optional($this->application->origin)->city;
            }),
            'experience_years' => $this->whenLoaded('application', function () {
                return optional($this->application->origin)->experience_years
                    ? $this->application->origin->experience_years . ' ans'
                    : null;
            }),
            'documents' => $this->application?->documents?->map(function ($doc) {
                return [
                    'uuid' => $doc->uuid,
                    'name' => $doc->name,
                    'path' => $doc->path,
                ];
            }),
            // 'documents' => [
            //     // (object)[
            //     //     'name'=> $this->whenLoaded('application',function(){
            //     //         return optional($this->application->documents[0])->name;
            //     //     }),
            //     //     'path'=>  $this->whenLoaded('application',function(){
            //     //         return optional($this->application->documents[0])->path;
            //     //     })
            //     //     ]
            //     //     ,
            //     // (object)[
            //     //     'name'=> $this->whenLoaded('application',function(){
            //     //         return optional($this->application->documents[0])->name;
            //     //     }),
            //     //     'path'=>  $this->whenLoaded('application',function(){
            //     //         return optional($this->application->documents[0])->path;
            //     //     })
            //     // ],
            // ],
            // 'french_level'       => optional($this->application->origin)->french_level,
            // 'english_level'      => optional($this->application->origin)->english_level,

            // Champs de documents (aplaties)
            // 'document_name' => optional($this->application->documents)->name,
            // 'document_path' => optional($this->application->documents)->path,
        ];
    }
}
