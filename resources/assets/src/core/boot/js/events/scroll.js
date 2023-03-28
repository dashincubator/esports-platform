/**
 *------------------------------------------------------------------------------
 *
 *  Delegate Scroll Events Using 'data-*' Attributes + Directive PubSub System
 *
 */

resolve(['directive', 'throttle'], function(directive, throttle) {

    'use strict';


    let directives = {
            'scroll': {
                fn: directive.fn
            },
            'stopscroll': {
                stop: true
            }
        },
        rootkey = 'root.scroll';


    document.addEventListener('scroll', throttle(directive.delegate(directives, rootkey), 16), {
        passive: true,
        capture: true
    });

});
