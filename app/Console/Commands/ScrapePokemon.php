<?php

namespace App\Console\Commands;

use App\Models\Pokemon;
use App\Spiders\PokemonDbSpider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use RoachPHP\Roach;

class ScrapePokemon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scrape-pokemon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrapes data from the pokemon DB site for seeding data locally.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!Storage::exists('pokemon_data_tmp.json')) {
            $this->info("Starting data scrap from PokemonDB.");
            Roach::startSpider(PokemonDbSpider::class);
        }

        if (!Storage::exists('pokemon_data_tmp.json')) {
            $this->error("Scrapped data still not available after scraping.");
            return Command::FAILURE;
        }

        $this->info("Inserting Pokemon to DB.");
        $data = array_values(json_decode(Storage::get('pokemon_data_tmp.json'), TRUE));

        Pokemon::insert($data);
        $this->info("Data successfully seeded.");

        return Command::SUCCESS;
    }
}
