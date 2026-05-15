<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documentation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'caption',
        'pictures',
    ];

    protected $casts = [
        'pictures' => 'array',
    ];


    public function postMaker(): BelongsTo {
        return $this->belongsTo(User::class,'user_id', 'id');
    }
}
