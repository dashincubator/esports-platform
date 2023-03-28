/**
 *------------------------------------------------------------------------------
 *
 *  Remove Element From DOM
 *
 */

resolve(['directive', 'dom', 'node'], function(directive, dom, node) {

    'use strict';


    let attribute = 'data-remove';


    const remove = function(e) {
        let id = this.getAttribute(attribute),
            target = this;

        if (id) {
            target = dom.id(id);
        }

        dom.update(() => {
            target.parentNode.removeChild(target);
        });
    };


    directive.on('remove', remove);

});
