<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecrutementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                       => $this->id,
            'uuid'                     => $this->uuid,
            'code_recrutement'         => $this->code_recrutement,
            'center'                   => $this->center,
            'country_duty_station'     => $this->country_duty_station,
            'city_duty_station'        => $this->city_duty_station,
            'position_title'           => $this->position_title,
            'recrutement_id'           => $this->recrutement_id,
            'contract_from'            => $this->contract_from,
            'contract_to'              => $this->contract_to,
            'date'                     => $this->date,
            'grade'                    => $this->grade,
            'division'                 => $this->division,
            'program'                  => $this->program,
            'sub_unit'                 => $this->sub_unit,
            'education_level'          => $this->education_level,
            'resources_type'           => $this->resources_type,
            'contract_time'            => $this->contract_time,
            'is_active'                => $this->is_active,
            'supervisor_name'          => $this->supervisor_name,
            'supervisor_position'      => $this->supervisor_position,
            'recruitment_type'         => $this->recruitment_type,
            'country_of_recruitment'   => $this->country_of_recruitment,
            'cgiar_workforce_group'    => $this->cgiar_workforce_group,
            'cgiar_group'              => $this->cgiar_group,
            'cgiar_appointed'          => $this->cgiar_appointed,
            'percent_time_other_center'=> $this->percent_time_other_center,
            'shared_working_arrangement'=> $this->shared_working_arrangement,
            'initiative_lead'          => $this->initiative_lead,
            'initiative_personnel'     => $this->initiative_personnel,
            'salary_post'              => $this->salary_post,
            'created_at'               => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'               => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
