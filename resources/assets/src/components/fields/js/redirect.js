/**
 *------------------------------------------------------------------------------
 *
 *  On Select Change Redirect
 *
 */

resolve(['directive', 'node'], function(directive, node) {

    'use strict';


    const redirect = function(e) {
        let tag = this;

        let current = window.location.pathname,
            value = tag.options[tag.selectedIndex].value;

        if ([value, value.replace('/1', '')].includes(current)) {
            return;
        }

        window.location.href = window.location.href.replace(current, value);
    };


    directive.on('field-redirect', redirect);

});
