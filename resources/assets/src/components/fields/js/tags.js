/**
 *------------------------------------------------------------------------------
 *
 *  Field Tag Badges
 *
 */

resolve(['alert', 'directive', 'dom', 'node'], function(alert, directive, dom, node) {

    'use strict';


    let attributes = {
            container: 'fieldTagsContainer',
            template: 'fieldTagsTemplate'
        };


    const button = function(e) {
        (tags.bind(this.parentNode.getElementsByClassName('field-mask')[0]))(e, true);
    };

    const tags = function(e, button = false) {

        // Enter Key
        if (e.keyCode !== 13 && button === false) {
            return;
        }

        e.preventDefault();

        let container = dom.id(this.dataset[attributes.container]),
            template = this.dataset[attributes.template];

        if (!this.value) {
            alert.error('Invalid value, try again!');

            setTimeout(() => {
                alert.deactivate.error();
            }, 1000 * 2);

            return;
        }

        node.update(container, {
            after: () => {
                this.value = '';
            },
            html: {
                append: template.replace(new RegExp('{value}', 'g'), this.value)
            }
        });
    };


    directive.on('field-tag-button', button);
    directive.on('field-tags', tags);

});
