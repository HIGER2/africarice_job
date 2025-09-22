<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CgiarInformation extends Model
{
    use HasFactory;
    protected $table='cgiar_informations';
    protected $fillable = ['application_id', 'uuid','current', 'cgiar_center', 'cgiar_email'];

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
