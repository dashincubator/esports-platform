/**
 *------------------------------------------------------------------------------
 *
 *  Delegate Click Events Using 'data-*' Attributes + Directive PubSub System
 *
 */

resolve(['directive'], function(directive) {

    'use strict';


    let directives = {
            'stoptouchmove': {
                stop: true
            },
            'touchmove': {
                fn: directive.fn
            }
        },
        rootkey = 'root.touchmove';


    document.addEventListener('touchmove', directive.delegate(directives, rootkey), true);

});
