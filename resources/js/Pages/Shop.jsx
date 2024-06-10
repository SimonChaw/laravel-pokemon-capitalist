import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { useEffect, useState } from 'react';
import { Head, usePage, useForm } from '@inertiajs/react';

export default function Dashboard({ auth, items, mons, flash }) {
    const { errors } = usePage().props;
    // Creating a JS "buy" feature, creating an id and taking them to the shop.buy route:
    const { post } = useForm();
    const [showErrorMessage, setShowErrorMessage] = useState(false); // this makes the message appear.

    const buyItem = (itemId) => {
        post(route('shop.buy', {
            item_id: itemId
        }))
        
        setShowErrorMessage(true);
        setTimeout(() => setShowErrorMessage(false), 2000);
    };

    const sellMon = (id) => {
        post(route('shop.sell', {
            user_pokemon_id: id
        }))
        setShowErrorMessage(true);
        setTimeout(() => setShowErrorMessage(false), 1000);
    }

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">PokeMart</h2>}
            message={flash.message}
        >
            <Head title="PokeMart" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">

                    {/* ITEMS TABLE */}
                    <div className="bg-white dark:bg-gray-800 file:shadow-sm sm:rounded-lg p-5">
                        <table className="border-collapse table-auto w-full text-sm">
                                <thead>
                                    <tr>
                                        <th className="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">Item Name</th>
                                        <th className="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">Icon</th>
                                        <th className="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">Cost</th>
                                        <th className="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">In Inventory</th>
                                        <th className="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left"></th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white dark:bg-slate-800">
                                    {/* this map is how you do a loop in JSX */}
                                    {items.map((item, index) => (
                                        <tr key={index}>
                                            <td className="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">{ item.name }</td>
                                            <td className="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400"><img className='w-10' src={ item.url } alt={ item.name } /></td>
                                            <td className="border-b border-slate-100 dark:border-slate-700 p-4 pr-8 text-slate-500 dark:text-slate-400">₽{ item.value }</td>
                                            <td className="border-b border-slate-100 dark:border-slate-700 p-4 pr-8 text-slate-500 dark:text-slate-400">{ item.users[0].pivot.quantity ?? 0 }</td>
                                                {/*IF there is a user in index-0, display value, else 0.*/}
                                            <td className="border-b border-slate-100 dark:border-slate-700 p-4 pr-8 text-slate-500 dark:text-slate-400 text-center">
                                                <button onClick={ () => buyItem(item.id) } className="rounded-md px-6 py-2 text-white bg-indigo-500">Buy</button>
                                                    {/* It's not "onClick={buyItem(item.id)" because this just passes the value of the executed function to onClick.
                                                        The way we officially have it, we pass a function TO "()" to execute as the button is clicked.  */}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                        </table>

                        
                    </div>
                    <div className='w-full flex justify-center'>
                        {errors.generic && showErrorMessage && (
                            <div className='p-4 bg-red-400 border-red-500 rounded-md'>
                                {errors.generic}
                            </div>
                        )}
                    </div>
                    <br></br>

                    {/* POKEMON TABLE */}
                    <div className="bg-white dark:bg-gray-800 file:shadow-sm sm:rounded-lg p-5">
                        <table className="border-collapse table-auto w-full text-sm">
                                <thead>
                                    <tr>
                                        <th className="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">Pokemon Name</th>
                                        <th className="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">Icon</th>
                                        <th className="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">Cost</th>
                                        <th className="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left"># Caught</th>
                                        <th className="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left"></th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white dark:bg-slate-800">
                                    {mons.map((userPokemon, index) => (
                                        <tr key={index}>
                                            <td className="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">{ userPokemon.pokemon.name }</td>
                                            <td className="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400"><img className='w-10' src={ userPokemon.pokemon.url } alt={ userPokemon.pokemon.name } /></td>
                                            <td className="border-b border-slate-100 dark:border-slate-700 p-4 pr-8 text-slate-500 dark:text-slate-400">₽{ userPokemon.pokemon.value }</td>
                                            <td className="border-b border-slate-100 dark:border-slate-700 p-4 pr-8 text-slate-500 dark:text-slate-400">{ userPokemon.quantity }</td>
                                            <td className="border-b border-slate-100 dark:border-slate-700 p-4 pr-8 text-slate-500 dark:text-slate-400 text-center">
                                                <button onClick={ () => sellMon(userPokemon.id) } className="rounded-md px-6 py-2 text-white bg-red-500">Sell</button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                        </table>
                    </div>

                    <div className='w-full flex justify-center'>
                        {errors.pokemon && showErrorMessage && (
                            <div className='p-4 bg-red-400 border-red-500 rounded-md'>
                                {errors.pokemon}
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}