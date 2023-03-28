/**
 *------------------------------------------------------------------------------
 *
 *  Delegate Click Events Using 'data-*' Attributes + Directive PubSub System
 *
 */

resolve(['directive'], function(directive) {

    'use strict';


    let directives = {
            'mouseup': {
                fn: directive.fn
            },
            'stopmouseup': {
                stop: true
            }
        },
        rootkey = 'root.mouseup';


    document.addEventListener('mouseup', directive.delegate(directives, rootkey), true);

});
