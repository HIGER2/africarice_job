<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Origin extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'nationality',
        'second_nationality',
        'country',
        'city',
        'experience_years',
        'french_level',
        'english_level',
        'uuid',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
}
