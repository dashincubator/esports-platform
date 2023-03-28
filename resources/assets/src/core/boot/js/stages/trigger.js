/**
 *------------------------------------------------------------------------------
 *
 *  Trigger Delegated DOM Event 'data-ref'(s) On Mounted
 *
 */

resolve(['dom', 'emitter', 'node'], function(dom, emitter, node) {

    'use strict';


    let events = [
            'blur',
            'change',
            'click',
            'focus',
            'hover',
            'keydown',
            'scroll',
            'wheel'
        ],
        prefix = 'trigger:';


    function dispatch(elements, type) {
        let e = new Event(type);

        for (let i = 0, n = elements.length; i < n; i++) {
            elements[i].dispatchEvent(e);
        }
    }

    function mounted() {
        for (let i = 0, n = events.length; i < n; i++) {
            let elements = dom.refs(`${prefix}${events[i]}`),
                type = events[i];

            if (elements) {
                dispatch(elements, type);
            }
        }

        emitter.dispatch('triggers.dispatched');
    }


    emitter.on('components.mounted', mounted);

});
