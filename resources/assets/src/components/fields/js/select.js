/**
 *------------------------------------------------------------------------------
 *
 *  On Select Change Update Mask
 *
 */

resolve(['directive', 'dom', 'node', 'state'], function(directive, dom, node, state) {

    'use strict';


    let change = new Event('change');


    const select = function() {
        let parent = this.closest('.field-text');

        let mask = parent.getElementsByClassName('field-mask')[0],
            tag = parent.getElementsByClassName('field-tag')[0],
            value = this.dataset.value;

        dom.update(() => {
            // Update Select HTML Tag Value
            tag.value = value;

            // Update Mask Text
            node.text(mask, tag.options[tag.selectedIndex].text);

            // Dispatch Update Event For Other Directives
            tag.dispatchEvent(change);

            // Update Option State
            state.deactivate(node.siblings(this));
            state.activate(this);
        });
    };


    directive.on('field-select', select);

});
