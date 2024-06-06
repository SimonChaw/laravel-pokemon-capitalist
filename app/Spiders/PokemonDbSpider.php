<?php

namespace App\Spiders;

use Generator;
use Illuminate\Support\Facades\Storage;
use RoachPHP\Events\RunFinished;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use Illuminate\Support\Str;

class PokemonDbSpider extends BasicSpider
{
    public array $startUrls = [
        "https://pokemondb.net/pokedex/game/firered-leafgreen"
    ];

    public array $rarePokemon = [
        "Mewtwo", "Mew", "Articuno", "Zapdos", "Moltres", "Dragonair", "Dragonite"
    ];

    protected array $pokemonData;

    public function parse(Response $response): Generator
    {
        $pokemonNamesAndLinks = $response->filter('.ent-name')->extract(['_text', 'href']);
        // $pokemonLinks = $response->filter('.ent-name')->links();

        foreach($pokemonNamesAndLinks as $i => $nameAndLink) {
            $this->pokemonData[$nameAndLink[0]] = [
                'name' => $nameAndLink[0],
            ];

            yield $this->request('GET', "https://pokemondb.net" . $nameAndLink[1], 'parseIndividualPokemon');
        }

        yield $this->item([
            'names' => $pokemonNamesAndLinks,
        ]);
    }

    public function parseIndividualPokemon(Response $response): Generator
    {
        $name = Str::replace([" (female)", " (male)"], "", $response->filter('h1')->text());
        $type = $response->filter('.type-icon')->text();
        $description = $response->filter('.firered')->closest('th')->siblings()->filter('td')->text();
        $image = $response->filter('p > a > picture > img')->attr('src');

        $this->pokemonData[$name]['type'] = $type;
        $this->pokemonData[$name]['description'] = $description;
        $this->pokemonData[$name]['url'] = $image;
        
        if (in_array($name, $this->rarePokemon)) {
            $this->pokemonData[$name]['rarity'] = rand(900, 1000);
        } else {
            $this->pokemonData[$name]['rarity'] = rand(0, 2) === 2 ? rand(500, 899) : rand(0, 499);
        }

        yield $this->item([
            'type' => $type,
            'description' => $description,
            'image' => $image
        ]);

        if ($name === "Mew") {
            Storage::put('pokemon_data_tmp.json', json_encode($this->pokemonData));
        }
    }
}