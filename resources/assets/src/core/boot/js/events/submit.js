/**
 *------------------------------------------------------------------------------
 *
 *  Delegate Click Events Using 'data-*' Attributes + Directive PubSub System
 *
 */

resolve(['directive'], function(directive) {

    'use strict';


    let directives = {
            'stopsubmit': {
                stop: true
            },
            'submit': {
                fn: directive.fn
            }
        },
        rootkey = 'root.submit';


    document.addEventListener('submit', directive.delegate(directives, rootkey), true);

});
