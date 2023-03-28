/**
 *------------------------------------------------------------------------------
 *
 *  Drag To Scroll Horizontally
 *
 */

resolve(['dom', 'directive', 'emitter', 'node'], function(dom, directive, emitter, node) {

    'use strict';


    let elements = new Map();


    const drag = function(e) {
        let data = elements[this] || {};

        if (!data.mouseDown || this.scrollTopMax + this.scrollLeftMax === 0) {
            return;
        }

        e.preventDefault();

        node.update(this, {
            attribute: {
                scrollLeft: data.scrollLeft - (((e.pageX || e.touches[0].pageX) - this.offsetLeft) - data.startX),
                scrollTop: data.scrollTop - (((e.pageY || e.touches[0].pageY) - this.offsetTop) - data.startY)
            }
        });
    };

    const start = function(e) {
        elements[this] = {
            mouseDown: true,
            scrollLeft: this.scrollLeft,
            scrollTop: this.scrollTop,
            startX: (e.pageX || e.touches[0].pageX) - this.offsetLeft,
            startY: (e.pageY || e.touches[0].pageY) - this.offsetTop,
            element: this
        };
    };

    const stop = function(e) {
        elements[this] = {
            mouseDown: false,
            scrollLeft: 0,
            scrollTop: 0,
            startX: 0,
            startY: 0
        };
    };


    directive.on('drag-move',  drag);
    directive.on('drag-start', start);
    directive.on('drag-stop',  stop);

});
