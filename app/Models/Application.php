<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Application extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'uuid',];

    public function diplomas()
    {
        return $this->hasMany(Diploma::class);
    }

    public function cgiarInformation()
    {
        return $this->hasOne(CgiarInformation::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function identification()
    {
        return $this->hasOne(Identification::class);
    }

    public function origin()
    {
        return $this->hasOne(Origin::class);
    }

    public function references()
    {
        return $this->hasMany(Reference::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'application_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
}
