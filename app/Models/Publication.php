<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'recrutement_id',
        'uuid',
        'type',
        'is_published',
        'is_closed',
        'published_by',
        'published_at',
        'uuid',
        'expires_at'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_closed' => 'boolean',
    ];

    public function candidatures()
    {
        return $this->hasMany(PublicationApplication::class, 'publication_id');
    }

    public function job()
    {
        return $this->belongsTo(Recrutement::class, 'recrutement_id');
    }

    public function ca()
    {
        return $this->belongsTo(Publication::class, 'publication_id');
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function files()
    {
        return $this->hasMany(PublicationFile::class);
    }

    // Scopes utiles
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where('is_closed', false);
    }

    public function scopeInternal($query)
    {
        return $query->where('type', 'internal');
    }

    public function scopePublic($query)
    {
        return $query->where('type', 'public');
    }

    public static function generateReference($model, $attribute = 'reference', $prefix = 'REF')
    {
        do {
            // Génère une référence aléatoire de type REF-XXXXX (5 caractères alphanumériques)
            $reference = $prefix . '-' . strtoupper(Str::random(6));
        } while ($model::where($attribute, $reference)->exists()); // vérifie l'unicité en base

        $model->{$attribute} = $reference;

        return $reference;
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
            $model->reference = self::generateReference($model, 'reference');
        });
    }
}
