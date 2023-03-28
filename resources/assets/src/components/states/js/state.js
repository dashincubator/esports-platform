/**
 *------------------------------------------------------------------------------
 *
 *  Activate/Deactive State Events
 *
 */

bind('state', {
    dependencies: ['emitter', 'node'],
    value: function(emitter, node) {

        'use strict';


        let classname = '--active';


        function shared(elements, action, state) {
            if (!elements) {
                return;
            }
            else if (!Array.isArray(elements)) {
                elements = [elements];
            }

            node.classname(elements, { [classname]: action });

            for (let i = 0, n = elements.length; i < n; i++) {
                let element = elements[i],
                    id = element.id;

                if (id) {
                    emitter.dispatch(`${id}.${state}`, {}, element);
                }
            }
        }


        const active = (element) => {
            return element.classList.contains(classname);
        };


        const activate = (elements) => {
            shared(elements, 'add', 'activated');
        };

        const deactivate = (elements) => {
            shared(elements, 'remove', 'deactivated');
        };

        const toggle = (elements) => {
            shared(elements, 'toggle', 'toggled');
        };


        return Object.freeze({ active, activate, deactivate, toggle });

    }
});
