import classNames from 'classnames';
import { useState } from 'react';

export default function PokeballButton({ items, click }) {

    const [selectedBall, setSelectedBall] = useState(items[0]);

    const hasPokeballs = selectedBall.pivot.quantity > 0;

    const throwBtnClasses = classNames(
        'bg-slate-800 dark:bg-slate-900 text-white mr-2 p-4 rounded-md flex items-center',
        {'disabled:opacity-75 cursor-not-allowed' : !hasPokeballs}
            // The part in '' is the classNames that are applied if the condition after : is met.
            // In this example, we apply 'disabled:opacity-75 cursor-not-allowed' as classes to our 
                //HTML element if pokeballs remaining is = to 0.
    );

    const ballSelected = (e) => {
        setSelectedBall(items.find((item) => item.id == e.target.value) ?? items[0]);
    };

    return (
        <div className='flex flex-col'>
            {/* Throw Pokeball button */}
            <button onClick={ () => click(selectedBall.id) } disabled={!hasPokeballs} className={throwBtnClasses}>
                <img src={selectedBall.url} className="w-5 mr-2" />
                Throw {selectedBall.name}! ({selectedBall.pivot.quantity})
            </button>

            <select className='rounded-sm mt-2 mr-2' onChange={ ballSelected }>
                {items.map((item, index) => (
                    <option className={ classNames(
                        {'disabled:opacity-75 cursor-not-allowed bg-gray-400' : item.pivot.quantity < 1}
                    )} key={ index } value={item.id} disabled={ item.pivot.quantity < 1}>{ item.name } ({ item.pivot.quantity })</option>
                ))}
            </select>
        </div>
    );
}