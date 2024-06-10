<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public function users()
    {
        // Returning a many-to-many relationship:
        return $this->belongsToMany(User::class, 'item_users')->withPivot('quantity');
            // connecting 'item_users' table to the 'User' model class, to use it.
    }
}