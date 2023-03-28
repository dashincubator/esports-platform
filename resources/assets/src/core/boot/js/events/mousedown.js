/**
 *------------------------------------------------------------------------------
 *
 *  Delegate Click Events Using 'data-*' Attributes + Directive PubSub System
 *
 */

resolve(['directive'], function(directive) {

    'use strict';


    let directives = {
            'mousedown': {
                fn: directive.fn
            },
            'stopmousedown': {
                stop: true
            }
        },
        rootkey = 'root.mousedown';


    document.addEventListener('mousedown', directive.delegate(directives, rootkey), true);

});
