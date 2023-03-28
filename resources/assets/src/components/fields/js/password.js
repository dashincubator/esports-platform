/**
 *------------------------------------------------------------------------------
 *
 *  Show/Hide Password
 *
 */

resolve(['directive', 'node'], function(directive, node) {

    'use strict';


    const password = function() {
        let input = this.parentElement.getElementsByClassName('field-tag')[0];

        node.update(input, {
            attribute: { type: input.type === 'password' ? 'text' : 'password' }
        });
    };


    directive.on('field-password', password);

});
