export default function Pokemon({ pokemon, caught=false }) {
    return (
        <div className="p-5 flex flex-col items-center justify-center relative">
            {caught && 
                <img className="h-8 absolute bottom-12 left-20" src="/images/ball.png" alt={"Picture of " + pokemon.name} />
            }
            <img className="h-32" src={pokemon.url} alt={"Picture of " + pokemon.name} />
            <p className="text-xl pt-4">{ pokemon.name }</p>
            <p className="text-xl pt-4">Rarity - { pokemon.rarity }</p>
            {/* If statement */}
            
        </div>
    );
}