<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemUser;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Artisan::call('app:scrape-pokemon');

        $user = User::factory()->create([
            'name' => 'Test Trainer',
            'email' => 'trainer@example.com',
        ]);

        $pokeball = Item::create([
            'name' => 'Pokeball',
            'value' => 100,
            'url' => '/images/ball.png'
        ]);

        // Give the user some pokeballs
        ItemUser::create([
            'item_id' => $pokeball->id,
            'user_id' => $user->id,
            'quantity' => 100
        ]);
    }
}
