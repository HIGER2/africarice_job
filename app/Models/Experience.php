<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = ['application_id','uuid','company_name','position','start_date','end_date','current'];

    public function application() {
        return $this->belongsTo(Application::class);
    }

    protected $casts = [
        'current' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
}
