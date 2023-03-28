/**
 *------------------------------------------------------------------------------
 *
 *  Delegate Click Events Using 'data-*' Attributes + Directive PubSub System
 *
 */

resolve(['directive'], function(directive) {

    'use strict';


    let directives = {
            'stoptouchend': {
                stop: true
            },
            'touchend': {
                fn: directive.fn
            }
        },
        rootkey = 'root.touchend';


    document.addEventListener('touchend', directive.delegate(directives, rootkey), {
        passive: true,
        capture: true
    });

});
