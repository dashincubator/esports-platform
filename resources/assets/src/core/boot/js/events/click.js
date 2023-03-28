/**
 *------------------------------------------------------------------------------
 *
 *  Delegate Click Events Using 'data-*' Attributes + Directive PubSub System
 *
 */

resolve(['directive'], function(directive) {

    'use strict';


    let directives = {
            'click': {
                fn: directive.fn
            },
            'stopclick': {
                stop: true
            }
        },
        rootkey = 'root.click';


    document.addEventListener('click', directive.delegate(directives, rootkey), true);

});
