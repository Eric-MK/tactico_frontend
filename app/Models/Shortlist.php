<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shortlist extends Model
{
    protected $fillable = [
        'user_id',
        'player_name',
        'position',
        'competition',
        'age',
        'player_type',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
