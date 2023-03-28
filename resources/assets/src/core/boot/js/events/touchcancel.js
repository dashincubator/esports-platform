/**
 *------------------------------------------------------------------------------
 *
 *  Delegate Click Events Using 'data-*' Attributes + Directive PubSub System
 *
 */

resolve(['directive'], function(directive) {

    'use strict';


    let directives = {
            'stoptouchcancel': {
                stop: true
            },
            'touchcancel': {
                fn: directive.fn
            }
        },
        rootkey = 'root.touchcancel';


    document.addEventListener('touchcancel', directive.delegate(directives, rootkey), true);

});
