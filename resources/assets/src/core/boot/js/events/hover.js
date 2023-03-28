/**
 *------------------------------------------------------------------------------
 *
 *  Delegate Click Events Using 'data-*' Attributes + Directive PubSub System
 *
 */

resolve(['directive'], function(directive) {

    'use strict';


    let directives = {
            'hover': {
                fn: directive.fn
            },
            'stophover': {
                stop: true
            }
        },
        rootkey = 'root.hover';


    document.addEventListener('mouseenter', directive.delegate(directives, rootkey), true);
    document.addEventListener('mouseleave', directive.delegate(directives, rootkey), true);

});
