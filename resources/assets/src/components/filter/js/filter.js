/**
 *------------------------------------------------------------------------------
 *
 *  Display Filtered Items Only
 *
 */

resolve(['directive', 'dom', 'emitter', 'node', 'state'], function(directive, dom, emitter, node, state) {

    'use strict';


    let attribute = 'data-filter',
        classname = 'filter',
        nodes = null;


    function active(element) {
        return element.classList.contains(classname) && state.active(element);
    }


    const filter = function() {
        let target  = [],
            value = this.value;

        if (value === 'all') {
            dom.update(() => {
                state.activate(nodes);
            });

            return;
        }

        for (let i = 0, n = nodes.length; i < n; i++) {
            let node = nodes[i];

            if (node.getAttribute(attribute) !== value) {
                continue;
            }

            target.push(node);
        }

        if (!target.length) {
            return;
        }

        dom.update(() => {
            let siblings  = node.siblings(target[0]);

            state.deactivate(siblings);
            state.activate(target);
        });
    };


    directive.on('filter', filter);
    emitter.on('dom.refs.cached', function() {
        nodes = dom.refs('filter') || [];
    });

});
