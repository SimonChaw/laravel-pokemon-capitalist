import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Pokemon from '@/Components/Pokemon';
import { Head } from '@inertiajs/react';

export default function Dex({ auth, pokemon }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Pokedex</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-wrap">
                        {/* This is a FOR loop. The .map takes 2 arguments, a current iteration of array and an index value */}
                        {pokemon.map((mon, index) => (
                            <div className="w-1/3" key={index}>
                                {/* Pokemon is the component (a reusable visual asset),
                                pokemon is the name of the passed-in property,
                                mon is the current iteration we're looping through, using the map */}
                                <Pokemon pokemon={mon} caught={mon.users.length > 0}/>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}