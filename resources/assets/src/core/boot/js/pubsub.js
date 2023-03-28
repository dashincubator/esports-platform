/**
 *------------------------------------------------------------------------------
 *
 *  Reusable Event/PubSub System
 *
 */

bind('pubsub', {
    singleton: false,
    value: function() {

        'use strict';


        let events = new Map();


        const dispatch = (key, data, context) => {
            let keys = key.split(',').map((k) => {
                return k.trim();
            });

            for (let i = 0, n = keys.length; i < n; i++) {
                let key = keys[i];

                if (!events.has(key)) {
                    continue;
                }

                let queue = events.get(key);

                queue.forEach((fn) => {
                    (context ? fn.bind(context) : fn)(data);

                    if (fn.__once) {
                        queue.delete(fn);
                    }
                });
            }
        };

        const off = (key, fn) => {
            if (typeof fn !== 'function') {
                return;
            }

            let keys = key.split(',').map((k) => {
                return k.trim();
            });

            for (let i = 0, n = keys.length; i < n; i++) {
                let key = keys[i];

                if (fn) {
                    events.get(key).delete(fn);
                }
                else {
                    events.delete(key);
                }
            }
        };

        const on = (key, fn) => {
            if (typeof fn !== 'function') {
                return;
            }

            let keys = key.split(',').map((k) => {
                return k.trim();
            });

            for (let i = 0, n = keys.length; i < n; i++) {
                let key = keys[i];

                if (!events.has(key)) {
                    events.set(key, new Set());
                }

                events.get(key).add(fn);
            }
        };

        const once = (key, fn) => {
            fn.__once = true;

            on(key, fn);
        };


        return Object.freeze({ dispatch, off, on, once });

    }
});
