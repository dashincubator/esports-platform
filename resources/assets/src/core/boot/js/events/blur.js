/**
 *------------------------------------------------------------------------------
 *
 *  Delegate Blur Events Using 'data-*' Attributes + Directive PubSub System
 *
 */

resolve(['directive'], function(directive) {

    'use strict';


    let directives = {
            'blur': {
                fn: directive.fn
            },
            'focusinout': {
                fn: directive.fn
            },
            'stopblur': {
                stop: true
            }
        },
        rootkey = 'root.blur';


    document.addEventListener('blur', directive.delegate(directives, rootkey), true);

});
