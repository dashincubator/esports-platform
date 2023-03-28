/**
 *------------------------------------------------------------------------------
 *
 *  Activate Element Click Event
 *
 */

resolve(['directive', 'dom', 'state'], function(directive, dom, state) {

    'use strict';


    let attribute = 'data-activate';


    const activate = function() {
        dom.update(() => {
            state.activate( dom.id( this.getAttribute(attribute) ) );
        });
    };


    directive.on('activate', activate);

});
