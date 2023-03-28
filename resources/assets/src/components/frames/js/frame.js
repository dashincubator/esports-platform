/**
 *------------------------------------------------------------------------------
 *
 *  Activate/Deactivate Frames
 *
 */

resolve(['directive', 'dom', 'node', 'state'], function(directive, dom, node, state) {

    'use strict';


    let attribute = 'data-frame',
        id = (k) => `frame-${k}`;


    const frame = function(e) {
        let target = dom.id( id(this.getAttribute(attribute)) ),
            trigger = this;

        if (!target) {
            target = dom.id(this.getAttribute(attribute));

            if (!target) {
                return;
            }
        }

        if (state.active(target)) {
            return;
        }
        
        let activate = [target];

        if (!trigger.hasAttribute('data-frame-ignore')) {
            activate = activate.concat([trigger]);
        }

        dom.update(() => {
            state.deactivate(node.siblings(trigger).concat(node.siblings(target)));
            state.activate(activate);
        });
    };


    directive.on('frame', frame);

});
