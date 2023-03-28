resolve(['directive', 'dom', 'state'], function(directive, dom, state) {

    'use strict';


    let active = [];


    const detoggle = (e) => {
        if (active.length === 0) {
            return;
        }

        dom.update(() => {
            state.deactivate(active);

            active = [];
        });
    };

    // For Tooltips That Should Toggle When Clicked ( Ex: Select Fields )
    const toggle = function(e) {
        (tooltip.bind(this))(e, true);
    };

    const tooltip = function(e, toggle = false) {
        let open = state.active(this),
            target = e.target,
            trigger = this,
            type = e.type;

        if (['click', 'focus', 'mouseenter'].includes(type)) {
            if (open && trigger !== target && !toggle) {
                return;
            }
        }
        else if (['blur', 'mouseleave'].includes(type)) {
            if (trigger.contains(target) && trigger !== target) {
                return;
            }
        }

        if (type === 'click') {
            e.preventDefault();
            e.stopPropagation();

            detoggle();

            if (open == false) {
                dom.update(() => {
                    active.push(trigger);
                    state.activate(trigger);
                });
            }
        }
        else {
            directive.dispatch('toggle', e, trigger);
        }
    };


    directive.on('root.click', detoggle);
    directive.on('tooltip', tooltip);
    directive.on('tooltip-detoggle', detoggle);
    directive.on('tooltip-toggle', toggle);

});
