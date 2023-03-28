/**
 *------------------------------------------------------------------------------
 *
 *  Register Modals Directives Using Overlay Module
 *
 */

resolve(['emitter'], function(emitter) {

    'use strict';


    let container = {
            id: 'modals',
            modifier: (k) => `modals--${k}`,
            modifiers: []
        },
        child = {
            attribute: {
                modifier: 'data-modifier',
                trigger: 'data-modal'
            },
            id: (k) => `modal-${k}`,
        },
        directives = {
            close: 'modals',
            trigger: 'modal'
        };


    function mount() {
        resolve('overlays')(child, container, directives);
    }


    emitter.on('components.mount', mount);

});
