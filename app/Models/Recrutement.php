<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Recrutement extends Model
{
    use HasFactory;
    protected $fillable = [
        'center',
        'uuid',

        'recrutement_id',
        'contract_from',
        'contract_to',

        'program',
        'sub_unit',
        'education_level',
        'resources_type',
        'contract_time',
        'supervisor_name',
        'supervisor_position',
        'recruitment_type',
        'country_of_recruitment',
        'cgiar_workforce_group',
        'cgiar_group',
        'cgiar_appointed',
        'percent_time_other_center',
        'shared_working_arrangement',
        'initiative_lead',
        'initiative_personnel',
        'salary_post',
        'reference',
        'grade',
        'division',
        'country_duty_station',
        'city_duty_station',
        'position_title',
        'manager',
        'reason',
        'reason_replacement',
        'assign_by',
    ];


    public function assign()
    {
        return $this->belongsTo(user::class, 'assign_by');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
}
