/**
 *------------------------------------------------------------------------------
 *
 *  Deactivate Element Click Event
 *
 */

resolve(['directive', 'dom', 'state'], function(directive, dom, state) {

    'use strict';


    let attribute = 'data-deactivate';


    const deactivate = function() {
        dom.update(() => {
            state.deactivate( dom.id( this.getAttribute(attribute) ) );
        });
    };


    directive.on('deactivate', deactivate);

});
