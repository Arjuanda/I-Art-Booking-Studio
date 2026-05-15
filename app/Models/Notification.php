<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{

    protected $fillable = [
        'user_id',
        'type',
        'data',
        'read_at'
    ];
    protected $casts = [
        'data' => 'array',
    ];
    public function notifMaker(): BelongsTo {
        return $this->belongsTo(User::class,'user_id', 'id');
    }
}