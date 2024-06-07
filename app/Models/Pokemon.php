<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;

    public function wasCaught(): bool
    {
        return 100 - rand(10, $this->rarity / 10) > 80;
    }
}
