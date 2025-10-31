<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrakingPublication extends Model
{
    protected $fillable = [
        'publication_id',
        'status',
        'comment',
        'date',
    ];

    public function jobApplication()
    {
        return $this->belongsTo(Publication::class);
    }
}
