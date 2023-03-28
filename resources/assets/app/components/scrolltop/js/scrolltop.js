/**
 *------------------------------------------------------------------------------
 *
 *  Manage Active State On ScrollTop Button
 *
 */

resolve(['directive', 'dom', 'state'], function(directive, dom, state) {

    'use strict';


    let attribute = 'data-scrolltop',
        id = (k) => `scrolltop-${k}`;


    const scrolltop = function() {
        let container = this,
            target = dom.id( id(container.getAttribute(attribute)) );

        if (!target) {
            return;
        }

        dom.update(() => {
            if (container.scrollTop > 100) {
                state.activate(target);
            }
            else {
                state.deactivate(target);
            }
        });
    };


    directive.on('scrolltop', scrolltop);

});
