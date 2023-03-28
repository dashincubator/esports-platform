/**
 *------------------------------------------------------------------------------
 *
 *  Transitions Are Disabled By Default To Prevent CSS Transition Flash Caused
 *  By Overlays
 *
 */

resolve(['emitter', 'node'], function(emitter, node) {

    'use strict';


    let html = document.documentElement,
        modifier = {
            overlay: 'html--overlay',
            ready: 'html--ready'
        };


    emitter.once('triggers.dispatched', () => {
        node.update(html, {
            classname: { [modifier.ready]: 'add' }
        });
    });


    emitter.on('overlay.activated', () => {
        node.update(html, {
            classname: { [modifier.overlay]: 'add' }
        });
    });

    emitter.on('overlay.deactivated', () => {
        node.update(html, {
            classname: { [modifier.overlay]: 'remove' }
        });
    });

});
