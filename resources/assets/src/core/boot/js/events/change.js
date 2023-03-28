/**
 *------------------------------------------------------------------------------
 *
 *  Delegate Change Events Using 'data-*' Attributes + Directive PubSub System
 *
 */

resolve(['directive'], function(directive) {

    'use strict';


    let directives = {
            'change': {
                fn: directive.fn
            },
            'stopchange': {
                stop: true
            }
        },
        rootkey = 'root.change';


    document.addEventListener('change', directive.delegate(directives, rootkey), true);

});
