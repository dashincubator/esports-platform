/**
 *------------------------------------------------------------------------------
 *
 *  Countdown Timer
 *
 */

resolve(['dom', 'emitter', 'node'], function(dom, emitter, node) {

    'use strict';


    let attribute = 'data-countdown',
        elements = [];


    const countdowns = function() {
        elements = dom.refs('countdown');

        if (!elements) {
            return;
        }

        setInterval(function() {
            let now = Date.now() / 1000;

            for (let i = 0, n = elements.length; i < n; i++) {
                let countdown = elements[i],
                    deadline = countdown.getAttribute(attribute),
                    text = '';

                let t = deadline - now,
                    days = Math.floor(t / (60 * 60 * 24)),
                    hours = Math.floor((t % (60 * 60 * 24)) / (60 * 60)),
                    minutes = Math.floor((t % (60 * 60)) / 60),
                    seconds = Math.floor(t % 60);

                if (days > 0) {
                    text += ` ${days} Day${days > 1 ? 's' : ''}`;
                }

                if (hours > 0) {
                    text += ` ${hours} Hour${hours > 1 ? 's' : ''}`;
                }

                if (minutes > 0) {
                    text += ` ${minutes} Minute${minutes > 1 ? 's' : ''}`;
                }

                if (seconds > 0) {
                    text += ` ${seconds} Second${seconds > 1 ? 's' : ''}`;
                }

                node.update(countdown, { text });
            }
        }, 1000);
    };


    emitter.on('dom.refs.cached', countdowns);

});
