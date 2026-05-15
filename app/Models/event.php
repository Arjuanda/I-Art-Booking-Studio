<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class event extends Model
{
    // Inisialisasi tabel events
    protected $table = 'events';
    protected $guarded =['id'];

    public function eventMaker(): BelongsTo {
        return $this->belongsTo(User::class,'user_id', 'id');
    }

}
