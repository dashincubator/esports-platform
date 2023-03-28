/**
 *------------------------------------------------------------------------------
 *
 *  Mount Components Once DOM Is Ready
 *
 */

resolve(['emitter'], function(emitter) {

    'use strict';


    function mount() {
        // Add 'mounted' To The End Of The Components Mount Loop
        emitter.once('components.mount', () => {
            emitter.dispatch('components.mounted');
        });

        emitter.dispatch('components.mount');
    }


    emitter.on('dom.refs.cached', mount);

});
