<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PublicationApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'publication_id',
        'application_type',
        'status',
        'date'
    ];

    protected $casts = [
        'identification' => 'array',
        'origin' => 'array',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function publication()
    {
        return $this->belongsTo(Publication::class, 'publication_id');
    }



    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
            $model->date = Carbon::now()->toDateString();
        });
    }
}
