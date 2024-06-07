import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Pokemon from '@/Components/Pokemon';
import { Head, useForm, usePage } from '@inertiajs/react';

export default function Dashboard({ auth, pokemon }) {

    const { get } = useForm();
    const findNewPokemon = () => {
        get(route('pokemon.encounter', {'new' : true}));
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">VICTORY!</h2>}
        >
            <Head title="Victory!" />

            <div className="py-12">
                <div className="max-w-2xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg py-8">
                        <div className="pt-4 flex items-center justify-center">
                            <Pokemon pokemon={pokemon} />
                        </div>

                        <div className="flex items-center justify-center">
                            <div className="p-4 border border-green-500 bg-green-400">You caught {pokemon.name}!</div>
                        </div>

                        <div className="pt-8 flex items-center justify-center">
                            {/* Find pokemon button */}
                            <button onClick={ findNewPokemon } className="bg-slate-800 dark:bg-slate-900 text-white p-4 rounded-md flex items-center">
                                Find New Pokemon
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}