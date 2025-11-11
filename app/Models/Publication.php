<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'recrutement_id',
        'uuid',
        'reference',
        'type',
        // 'is_published',
        'status',
        'published_by',
        'published_at',
        'expires_at'
    ];

    protected $casts = [
        // 'is_published' => 'boolean',
        // 'is_closed' => 'boolean',
    ];

    public function trakings()
    {
        return $this->hasMany(TrakingPublication::class, 'publication_id');
    }

    public function lastTracking()
    {
        return $this->hasOne(TrakingPublication::class, 'publication_id')
            ->ofMany('created_at', 'max'); // récupère celui avec la date max
    }


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
        return $query->where('status', 'open');
    }


    public function scopeDate($query)
    {
        $now = Carbon::today('Africa/Abidjan');

        return $query->where('status', 'open')
            ->where('published_at', '<=', $now)
            ->where('expires_at', '>=', $now);
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

    public function updateStatus($data)
    {
        // 1. Créer un tracking
        $this->trakings()->create($data);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
            if (empty($model->reference)) {
                $model->reference = self::generateReference($model, 'reference');
            }
        });
    }
}
