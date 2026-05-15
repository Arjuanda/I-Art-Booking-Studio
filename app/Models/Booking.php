<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    public function getDisplayStatusAttribute()
    {
        if ($this->status !== 'approved') {
            return $this->status;
        }

        if (now()->between($this->start, $this->end)) {
            return 'On Going';
        }

        return $this->status;
    }
    use HasFactory;

    protected $fillable = [
        'start',
        'end',
        'color',
        'status',
        'user_id',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function bookMaker(): BelongsTo {
        return $this->belongsTo(User::class,'user_id', 'id');
    }
}
