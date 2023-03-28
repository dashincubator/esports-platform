/**
 *------------------------------------------------------------------------------
 *
 *  Copy Value To Clipboard
 *
 */

resolve(['directive', 'dom', 'alert'], function(directive, dom, alert) {

    'use strict';


    let attribute = 'data-copy',
        id = (k) => `copy-${k}`;


    const copy = function() {
        let target = dom.id( id(this.getAttribute(attribute)) );

        if (!target) {
            return;
        }

        target.select();

        document.execCommand('copy');

        alert.success('Copied to clipboard!', 2);
    };


    directive.on('copy', copy);

});
