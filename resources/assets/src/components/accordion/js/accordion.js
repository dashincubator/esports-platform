/**
 *------------------------------------------------------------------------------
 *
 *  Set Max Height Of Accordion Element
 *
 */

resolve(['directive', 'dom', 'node', 'state'], function(directive, dom, node, state) {

    'use strict';


    let attribute = 'data-accordion',
        classname = 'accordion',
        id = (k) => `accordion-${k}`;


    function active(element) {
        return element.classList.contains(classname) && element.style.maxHeight > '0px';
    }


    const accordion = function() {
        let target  = dom.id( id(this.getAttribute(attribute)) ),
            trigger = this;

        if (!target) {
            return;
        }

        if (active(target)) {
            node.update(target, {
                style: { maxHeight: '0px' },

                before: () => {
                    state.deactivate([trigger, target]);
                }
            });
        }
        else {
            node.update(target, {
                style: {
                    maxHeight: `${target.scrollHeight}px`
                },

                // Deal with nested accordions
                after: () => {
                    let element = target.parentNode,
                        parents = [];

                    while (element.classList !== undefined) {
                        if (element.classList.contains(classname)) {
                            parents.push(element);
                        }

                        element = element.parentNode;
                    }

                    if (parents.length > 0) {
                        node.style(parents, { maxHeight: `999px` });
                    }
                },
                before: () => {
                    let targets  = node.siblings(target, active),
                        triggers = node.siblings(trigger);

                    state.deactivate(triggers.concat(targets));
                    state.activate([trigger, target]);

                    node.style(targets, { maxHeight: '0px' });
                }
            });
        }
    };


    directive.on('accordion', accordion);

});
