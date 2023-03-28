/**
 *------------------------------------------------------------------------------
 *
 *  Add Processing Class To Form Button
 *
 */

resolve(['directive', 'node'], function(directive, node) {

    'use strict';


    let attribute = 'data-submit',
        modifier = 'button--processing';


    const processing = function(e) {
        let targets = Array.from(this.getElementsByTagName('button'));

        if (!targets) {
            return;
        }

        node.update(targets, {
            classname: { [modifier]: 'add' }
        });
    };


    directive.on('processing', processing);

});
