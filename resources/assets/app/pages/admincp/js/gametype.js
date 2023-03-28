/**
 *------------------------------------------------------------------------------
 *
 *  Active Gametype Frames On Field Select
 *
 */

resolve(['directive', 'dom'], function(directive, dom) {

    'use strict';


    let click = new Event('click'),
        id = (id) => `frame-trigger-gametype-${id}`;


    const select = function(e) {
        let game = e.target.value,
            target = dom.id( id(game) );

        if (!target) {
            return;
        }

        target.dispatchEvent(click);
    };


    directive.on('game-select', select);

});
