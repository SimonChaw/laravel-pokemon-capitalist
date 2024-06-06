<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PokemonController extends Controller
{
    public function encounter()
    {
        $rarity = rand(0, 1000);
        $pokemon = Pokemon::where('rarity', '<=', $rarity)->inRandomOrder()->first();

        return Inertia::render('Pokemon/Encounter', [
            'pokemon' => $pokemon,
        ]);
    }
}
