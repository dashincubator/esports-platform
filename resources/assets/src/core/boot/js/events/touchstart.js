/**
 *------------------------------------------------------------------------------
 *
 *  Delegate Click Events Using 'data-*' Attributes + Directive PubSub System
 *
 */

resolve(['directive'], function(directive) {

    'use strict';


    let directives = {
            'stoptouchstart': {
                stop: true
            },
            'touchstart': {
                fn: directive.fn
            }
        },
        rootkey = 'root.touchstart';


    document.addEventListener('touchstart', directive.delegate(directives, rootkey), {
        passive: true,
        capture: true
    });

});
