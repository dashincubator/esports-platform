/**
 *------------------------------------------------------------------------------
 *
 *  Delegate Click Events Using 'data-*' Attributes + Directive PubSub System
 *
 */

resolve(['directive', 'throttle'], function(directive, throttle) {

    'use strict';


    let directives = {
            'mousemove': {
                fn: directive.fn
            },
            'stopmousemove': {
                stop: true
            }
        },
        rootkey = 'root.mousemove';


    document.addEventListener('mousemove', throttle(directive.delegate(directives, rootkey), 16), true);

});
