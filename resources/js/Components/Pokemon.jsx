import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Dashboard({ auth, pokemon }) {
    return (
        <div className="w-40 h-40 p-5 flex flex-col items-center justify-center">
            <img className="h-32" src={pokemon.url} alt={"Picture of " + pokemon.name} />
            <p className="text-xl pt-4">{ pokemon.name }</p>
            <p className="text-xl pt-4">Rarity - { pokemon.rarity }</p>
        </div>
    );
}
