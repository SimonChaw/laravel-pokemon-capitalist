<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPokemon extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function pokemon() {
        return $this->belongsTo(Pokemon::class);
    }
}
