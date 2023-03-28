/**
 *------------------------------------------------------------------------------
 *
 *  Delegate keyup Events Using 'data-*' Attributes + Directive PubSub System
 *
 */

resolve(['directive'], function(directive) {

    'use strict';
 

    let directives = {
            'keyup': {
                fn: directive.fn
            }
        },
        rootkey = 'root.keyup';


    document.addEventListener('keyup', directive.delegate(directives, rootkey), true);

});
