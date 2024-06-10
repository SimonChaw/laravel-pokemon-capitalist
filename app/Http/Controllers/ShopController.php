<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemUser;
use App\Models\Pokemon;
use App\Models\UserPokemon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ShopController extends Controller
{
    public function index(Request $request) {
        $user = $request->user();
        $items = Item::query()
            ->with(['users' => fn($query) => $query->where('user_id', $user->id)])
            ->get();
            // "with" lets us execute relationships, when it executes.
            // "with" also lets us specify the user we want to get values from. The function modifies the query.

        // TO DO: Load all the pokemon that the user has caught user $user->pokemon
        // Display it in the shop, using a table that is the same as the items table,
            // but instead you have a sell button.
        $pokemon = UserPokemon::where('user_id', $user->id)->with('pokemon')->get();

        dd($pokemon);
        return inertia('Shop', [
            'items' => $items,
            'mons' => $pokemon
        ]);
    }

    public function buy(Request $request, int $item_id) {
        // First, get the item the user wants to buy:
        $item = Item::find($item_id);
        // Then, we want to check if the user has enough money to buy it:
        $user = $request->user(); //  money is stored in user's "money".
        if ($user->money < $item->value) {
            return redirect()->route('shop.index')->withErrors(['generic' => "You don't have enough money to buy that item."]);
        }
        // If they can buy it, deduct from their money, and add the item to their inventory:
        $user->decrement('money', $item->value);
        $itemUser = ItemUser::firstOrCreate([ // Modifying the record.
            // Checking if a user HAS an item:
            'user_id' => $user->id,
            'item_id' => $item->id
        ], [
            'quantity' => 0 // Setting ownership quantity to 0.
        ]);
        $itemUser->increment('quantity'); // Adding the recently purchased item to the quantity, piling up the owned items.
        // Return them to the shop-page and let them know the item was successfully purchased.
        Session::flash('message', "You purchased 1 {$item->name}");
        return redirect()->route('shop.index'); // redirecting back to shop.
    }

    public function sell(Request $request, int $pokemon_id) {
        // Find the pokemon that the user wants to sell.
        $pokemon = UserPokemon::find($pokemon_id); // Tracks all the pokemon that the user has.

        // If the user doesn't have the pokemon, return an error to the shop page.
        if (!$pokemon)
            return redirect()->route('shop.index')->withErrors(['generic' => "You don't have any of that pokemon left."]);
        // Otherwise, sell the pokemon and decrement the quantity in the UserPokemon record.
        // Add the money that the pokemon is worth to the trainer (exponential function based on rarity in pokemon model).
        //$userMonSell->decrement();
        //$user->increment('money', $pokemon->value);
        // Redirect back to shop with a success message.
        Session::flash('message', "You sold {$pokemon->name}");
        return redirect()->route('shop.index');
    }
}
