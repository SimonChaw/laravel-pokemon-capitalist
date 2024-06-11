import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Pokemon from '@/Components/Pokemon';
import { Head, useForm, usePage, router } from '@inertiajs/react';
import PokeballButton from '@/Components/PokeballButton';

export default function Encounter({ auth, pokemon, items }) {

    {/* Functions exist in JSX files so that code can be run on the client side.
        This allows clients to navigate between pages or do operations without
        having to reload the entire page. */}

    const { get, post } = useForm();
    const {errors} = usePage().props;

    const findNewPokemon = () => {
        get(route('pokemon.encounter', {'new' : true}));
    };

    const attemptCatch = (pokeballId) => {
        router.post(route('pokemon.catch'), { pokeball_id : pokeballId })
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">A wild { pokemon.name } appeared!</h2>}
        >
            <Head title={"A wild " + pokemon.name + " appeared!"} />

            <div className="py-12">
                <div className="max-w-2xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg py-8">
                        {/*{ pokeballsRemaining }*/}
                        <div className="pt-4 flex items-center justify-center">
                            <Pokemon pokemon={pokemon} />
                        </div>

                        <div className="flex items-center justify-center">
                            {errors.generic && <div className="p-4 border border-red-500 bg-red-400">{errors.generic}</div>}
                        </div>

                        <div className="pt-8 flex justify-center">
                            <PokeballButton click={ attemptCatch } items={ items } />

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
