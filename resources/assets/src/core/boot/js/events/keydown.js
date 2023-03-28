/**
 *------------------------------------------------------------------------------
 *
 *  Delegate Keydown Events Using 'data-*' Attributes + Directive PubSub System
 *
 */

resolve(['directive'], function(directive) {

    'use strict';


    let directives = {
            'keydown': {
                fn: directive.fn
            }
        },
        rootkey = 'root.keydown';


    document.addEventListener('keydown', directive.delegate(directives, rootkey), true);

});
