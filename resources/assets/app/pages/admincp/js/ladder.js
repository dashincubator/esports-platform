/**
 *------------------------------------------------------------------------------
 *
 *  Activate Ladder Options On Select
 *
 */

resolve(['directive', 'dom'], function(directive, dom) {

    'use strict';


    let click = new Event('click'),
        id = (id) => `frame-trigger-format-${id}`;


    const select = function(e) {
        let format = e.target.value,
            target = dom.id( id(format) );

        if (!target) {
            return;
        }

        target.dispatchEvent(click);
    };


    directive.on('format-select', select);

});
