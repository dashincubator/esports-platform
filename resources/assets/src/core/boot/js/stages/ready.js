/**
 *------------------------------------------------------------------------------
 *
 *  Dispatch DOM Ready Event
 *
 */

resolve(['emitter'], function(emitter) {

    'use strict';


    function ready() {
        emitter.dispatch('dom.ready');
    }


    if (['complete', 'interactive', 'loaded'].includes(document.readyState)) {
        ready();
    }
    else {
        document.addEventListener('DOMContentLoaded', ready);
    }

});
