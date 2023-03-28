bind('dom/update', function() {

    'use strict';


    let raf = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function (fn) {
            return window.setTimeout(fn, 1000 / 60);
        };

    let running = false,
        stack = [],
        task;


    function frame() {
        running = true;

        while (task = stack.shift()) {
            task();
        }

        running = false;
    }


    // Add Function To Stack -> Start RAF Loop
    const update = (fn) => {
        if (typeof fn !== 'function') {
            return;
        }

        stack.push(fn);

        if (!running) {
            raf(frame);
        }
    };


    return update;

});
