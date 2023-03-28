/**
 *------------------------------------------------------------------------------
 *
 *  Dispatch Resize Event
 *
 */

resolve(['emitter'], function(emitter) {

    'use strict';


    function resize() {
        emitter.dispatch('window.resize');
    }


    window.addEventListener('resize', resize);

});
