/**
 *------------------------------------------------------------------------------
 *
 *  Change File Name On Upload
 *
 */

resolve(['directive', 'node'], function(directive, node) {

    'use strict';


    const upload = function() {
        let parent = this.closest('.field-text');

        let mask = parent.getElementsByClassName('field-mask')[0];

        node.update(mask, {
            html: `<b>${this.files[0].name}</b>`
        });
    };


    directive.on('field-upload', upload);

});
