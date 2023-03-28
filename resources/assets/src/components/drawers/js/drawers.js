/**
 *------------------------------------------------------------------------------
 *
 *  Register Drawer Directives Using Overlay Module
 *
 */

resolve(['emitter'], function(emitter) {

    'use strict';


    let container = {
            id: 'drawers',
            modifier: (k) => `drawers--${k}`,
            modifiers: ['grey', 'transparent']
        },
        child = {
            attribute: {
                modifier: 'data-modifier',
                trigger: 'data-drawer'
            },
            id: (k) => `drawer-${k}`,
        },
        directives = {
            close: 'drawers',
            trigger: 'drawer'
        };


    function mount() {
        resolve('overlays')(child, container, directives);
    }


    emitter.on('components.mount', mount);

});
