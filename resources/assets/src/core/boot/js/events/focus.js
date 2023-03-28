/**
 *------------------------------------------------------------------------------
 *
 *  Delegate Focus Events Using 'data-*' Attributes + Directive PubSub System
 *
 */

resolve(['directive'], function(directive) {

    'use strict';


    let directives = {
            'focus': {
                fn: directive.fn
            },
            'focusinout': {
                fn: directive.fn
            },
            'stopfocus': {
                stop: true
            }
        },
        rootkey = 'root.focus';


    document.addEventListener('focus', directive.delegate(directives, rootkey), true);

});
