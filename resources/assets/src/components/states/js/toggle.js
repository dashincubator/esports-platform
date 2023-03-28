/**
 *------------------------------------------------------------------------------
 *
 *  Toggle State Click Event
 *
 */

resolve(['directive', 'dom', 'state'], function(directive, dom, state) {

    'use strict';


    let active = [],
        attribute = 'data-toggle';


    const deactivate = (element) => {
        let index = active.indexOf(element);

        if (index > -1) {
            active.splice(index, 1);
        }

        dom.update(() => {
            state.deactivate(element);
        });
    };

    const detoggle = (e) => {
        if (active.length === 0) {
            return;
        }

        let target = e.target;

        if (target) {
            target = dom.id( target.getAttribute(attribute) ) || target;
        }

        for (let i in active) {
            let element = active[i];

            if (element === target || element.contains(target)) {
                continue;
            }

            deactivate(element);
        }
    };

    const toggle = function(e) {
        let trigger = this,
            target = dom.id( trigger.getAttribute(attribute) ) || trigger,
            type = e.type;

        if (['focus', 'mouseenter'].includes(type)) {
            type = 'activate';
        }
        else if (['blur', 'mouseleave'].includes(type)) {
            type = 'deactivate';
        }
        else {
            type = 'toggle';
        }

        // 'target' Is Being Deactivated
        if (state.active(target)) {
            deactivate(target);

            if (target !== trigger) {
                deactivate(trigger);
            }
        }
        else {
            detoggle(e);

            if (target !== trigger) {
                active.push(trigger);

                dom.update(() => {
                    state[type](trigger);
                });
            }

            active.push(target);

            dom.update(() => {
                state[type](target);
            });
        }
    };


    directive.on('root.click', detoggle);
    directive.on('toggle', toggle);

});
