/**
 *------------------------------------------------------------------------------
 *
 *  Simple Throttle Function
 *
 */

bind('throttle', {
    value: function() {

        'use strict';


        const throttle = function(fn, throttle = 0) {
            let time = Date.now();

            return function(args) {
                if ((time + throttle - Date.now()) > 0) {
                    return;
                }

                time = Date.now();

                return fn(args);
            };
        };


        return throttle;

    }
});
