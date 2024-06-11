<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;

    protected $appends = ['value'];

    public function wasCaught(Item $item): bool
    {
        $escapeRate = match($item->name) {
            'Pokeball' => 95,
            'Great Ball' => 80,
            'Ultra Ball' => 60
        };
        return 100 - rand(10, $this->rarity / 10) > $escapeRate;
    }

    public function getValueAttribute()
    {
        $a = 0.05; // Quadratic coefficient
        $b = 0.1;    // Linear coefficient
        $c = 10;     // Constant term
        $x = $this->rarity;

        // Calculate the price using the quadratic function
        $price = $a * $x * $x + $b * $x + $c;

        // Return the price, formatted to 2 decimal places
        return round($price);
    }
}
