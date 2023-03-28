/**
 *------------------------------------------------------------------------------
 *
 *  Change File Name On Upload
 *
 */

resolve(['directive', 'dom', 'node'], function(directive, dom, node) {

    'use strict';


    let attribute = 'data-upload-update',
        id = (k) => `upload-${k}`,
        reader = new FileReader();


    const update = function() {
        let file = this.files[0],
            target = dom.id( id(this.getAttribute(attribute)) );

        if (!target) {
            return;
        }

        reader.onloadend = () => {
            let image = reader.result,
                update = {
                    style: { 'backgroundImage': `url(${image})` }
                };

            if (target.tagName === 'IMG') {
                update = {
                    attribute: { 'src': image }
                };
            }

            node.update(target, update);
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    };


    directive.on('upload-update', update);

});
