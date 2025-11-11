<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationUser extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...(new CanditureUser($this->user))->toArray($request),
            "date_application" => Carbon::parse($this->date)->format('d/m/Y'),
        ];

        // return array_merge(
        //     [
        //         "date_application" => $this->date,
        //     ],
        //     (new CanditureUser($this->user))->toArray($request)
        // );
    }
}
