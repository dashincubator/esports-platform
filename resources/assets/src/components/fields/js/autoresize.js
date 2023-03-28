/**
 *------------------------------------------------------------------------------
 *
 *  Autoresize Textarea On Keypress
 *
 */

resolve(['directive', 'node'], function(directive, node) {

    'use strict';


    const autoresize = function() {
        let tag = this;

        if (tag.offsetHeight > tag.scrollHeight) {
            return;
        }

        node.update(this, {
            style: { height: `${tag.scrollHeight}px` }
        }); 
    };


    directive.on('field-autoresize', autoresize);

});
