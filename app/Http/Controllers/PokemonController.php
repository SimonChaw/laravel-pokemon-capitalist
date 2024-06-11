<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use App\Models\UserPokemon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class PokemonController extends Controller
{
    public function encounter(Request $request)
    {
        $user = $request->user(); // returns user logged into the current sesh.
        $new = $request->get('new');
        
        // If a current session has a "UserEncounter" key, get the value attached to it.
            // Also checking if a new 
        if (!$new && Session::has("UserEncounter")) {
            $pokemon = Session::get("UserEncounter");
        }
        // If no key exists yet for a current session, generate a new pokemon to store to the session.
        else {
            // Generate a random number between 0 and 1
            $u = mt_rand() / mt_getrandmax();

            // Use the inverse transform sampling method to generate an exponentially distributed number
            // Adjust the lambda parameter to control the skewness
            $lambda = 0.005; // Higher lambda means fewer high values

            // Inverse transform sampling formula for exponential distribution
            $randomNumber = -log(1 - $u) / $lambda;

            // Ensure the number is within the desired range (0 to 1000)
            $rarity = min($randomNumber, 1000);

            $pokemon = Pokemon::where('rarity', '<=', $rarity)->orderBy('rarity', 'desc')->first();
            Session::put("UserEncounter", $pokemon);
            // Put accepts a key and a value.
                // Key is how we remember how to retrieve our value later.
                // The $pokemon value is storing it into this session.
        }

        // Passing the variables/keys some of their own variables of the same names
            //that Encounter.jsx can use.
        return inertia('Pokemon/Encounter', [
            'pokemon' => $pokemon,
            'items' => $user->items
        ]);
    }

    public function catch(Request $request) {
        $messages = [
            "Ah! It broke free!",
            "Arrgh. So close!",
            "It broke free! I almost had it."
        ];
        $lastMessage = Session::get("LastEncounterMessageIndex", 0);

        // First, validate that the user has at LEAST 1 pokeball:
        $user = $request->user(); // gives current logged in user.

        // Get the trainer's pokeballs:
        $pokeballs = $user->items()->where('items.id', $request->input('pokeball_id'))->first();
            // Going to user's items relationship, acting on where clause.
            // "first()" is used to get the Pokeball column in the name row inside the items table.
                // Allows us to access Pokeball by itself without it being wrapped in a collection.
        if (is_null($pokeballs) || $pokeballs->pivot->quantity === 0) {
            return redirect(route('pokemon.encounter'))->withErrors(['generic'=> "You don't have any {$pokeballs->name}s!"]);
            // "quantity" is in the pivot table, so we can use the same list of items in the shop
                // or a trainer's inventory, without worrying about it being connected to a trainer
                // and the quantity of that item they have.
        }

        // Change item-user record to substract pokeballs:
        DB::table('item_users')->where('user_id', $user->id)->where('item_id', $pokeballs->id)->decrement('quantity', 1);
            // All this baloney is important because it identifies which user's items to affect,
                // as well as which specific item to affect.
            // Without it, the quantity would be affected for every user and every item.

        
        $pokemon = Session::get("UserEncounter");
        
        // Check if the pokemon was caught.
        if (!$pokemon->wasCaught($pokeballs))
        {
            Session::put("LastEncounterMessageIndex", $lastMessage === 2 ? 0 : $lastMessage + 1);
            return redirect(route('pokemon.encounter'))->withErrors(['generic' => $messages[$lastMessage]]);
        }
        //else:
            
            $userPokemon = UserPokemon::firstOrCreate([ // storing caught pokemon to db:
                'user_id' => $user->id,
                'pokemon_id' => $pokemon->id
            ], [
                'quantity' => 0 // Setting ownership quantity to 0.
            ]);
            $userPokemon->increment('quantity');

            return inertia('Pokemon/Victory', ['pokemon' => $pokemon]);
    }

    public function dex(Request $request)
    {
        $user = $request->user(); // getting the user info out of the request.

        // this query is tied/scoped to the user:
        $pokemon = Pokemon::query()->with([
            'users' => fn($query) => $query->where('user_id', $user->id)->select('users.id')
        ])->get();
        return inertia('Pokemon/Dex', ['pokemon' => $pokemon]);
    }
}