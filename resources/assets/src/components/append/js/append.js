/**
 *------------------------------------------------------------------------------
 *
 *  Append Html Template To Container
 *
 */

resolve(['directive', 'dom', 'node'], function(directive, dom, node) {

    'use strict';


    let attributes = {
            container: 'appendContainer',
            template: 'appendTemplate'
        },
        id = 0;


    const append = function(e) {
        e.preventDefault();

        let container = dom.id(this.dataset[attributes.container]),
            template = this.dataset[attributes.template];

        node.update(container, {
            after: () => {
                id++;
            },
            html: {
                append: template.replace(new RegExp('{id}', 'g'), id)
            }
        });
    };


    directive.on('append', append);

});
