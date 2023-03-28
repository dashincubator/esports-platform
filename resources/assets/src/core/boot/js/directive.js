/**
 *------------------------------------------------------------------------------
 *
 *  Provides Listener Function To Process Data Attribute On Delegated Events
 *
 */

bind('directive', {
    dependencies: ['pubsub'],
    value: function(self) {

        'use strict';


        // Returns Event Listener For Delegated Events
        const delegate = (directives, rootkey) => {
            let dataset,
                haystack = Object.keys(directives),
                matches,
                target;

            // Ignore Delegated Event
            if (!haystack.length) {
                return () => {};
            }

            return function(e) {
                target = e.target;

                while (target) {
                    dataset = target.dataset;

                    if (dataset) {
                        matches = Object.keys(dataset).filter((key) => haystack.includes(key));

                        if (matches.length) {
                            for (let i = 0, n = matches.length; i < n; i++) {
                                let { fn, stop } = directives[matches[i]];

                                if (fn) {
                                    fn(dataset[matches[i]], e, target);
                                }

                                if (stop === true) {
                                    e.stopPropagation();
                                }
                            }

                            return;
                        }
                    }

                    target = target.parentNode;
                }

                // Delegated Event Bubbled Up To Root Element
                self.dispatch(rootkey, e);
            };
        };

        // Common Function Used By Delegated Events
        const fn = (value, e, element) => {
            self.dispatch(value, e, element);
        };


        return Object.freeze(Object.assign({ delegate, fn }, self));

    }
});
