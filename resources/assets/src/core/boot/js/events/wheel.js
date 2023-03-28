/**
 *------------------------------------------------------------------------------
 *
 *  Delegate Wheel Events Using 'data-*' Attributes + Directive PubSub System
 *
 */

resolve(['directive', 'throttle'], function(directive, throttle) {

    'use strict';


    let directives = {
            'stopwheel': {
                stop: true
            },
            'wheel': {
                fn: directive.fn
            }
        },
        rootkey = 'root.wheel';


    // Disabling Passive Event For Scrollers
    document.addEventListener('wheel', directive.delegate(directives, rootkey), {
        passive: false,
        capture: true
    });

});
