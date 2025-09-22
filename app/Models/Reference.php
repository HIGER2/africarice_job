<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Reference extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'application_id',
        'full_name',
        'function',
        'phone',
        'email'
    ];

    public function application() {
        return $this->belongsTo(Application::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
}
