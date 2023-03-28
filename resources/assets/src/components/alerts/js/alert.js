/**
 *------------------------------------------------------------------------------
 *
 *  Trigger/Create Alert
 *
 */

bind('alert', {
    dependencies: ['dom', 'node', 'render', 'state'],
    value: function(dom, node, render, state) {

        'use strict';


        let id = {
                alert: (key) => `alert-${key}`,
                messages:  (key) => `alert-${key}-messages`
            },
            template = (message) => `<span class='alert-message'><p>${message}</p></span>`;


        function activate(key, values, seconds = 0) {
            let alert = dom.id( id.alert(key) ),
                messages = dom.id( id.messages(key) );

            if (!Array.isArray(values)) {
                values = [values];
            }

            values = values.filter(Boolean);

            if (!alert || !messages || values.length < 1) {
                return;
            }

            node.update(messages, {
                html: render.template(template, values),

                after: () => {
                    state.activate(alert);

                    if (!seconds) {
                        return;
                    }

                    setTimeout(() => {
                        deactivate(key);
                    }, 1000 * seconds);
                }
            });
        }

        function deactivate(key) {
            let alert = dom.id( id.alert(key) );

            dom.update(() => {
                state.deactivate(alert);
            });
        }


        const error = (messages, seconds = 0) => {
            activate('error', messages, seconds);
        };

        const info = (messages, seconds = 0) => {
            activate('info', messages, seconds);
        };

        const success = (messages, seconds = 0) => {
            activate('success', messages, seconds);
        };


        const messages = (messages) => {
            error(messages.error || []);
            info(messages.info || []);
            success(messages.success || []);
        };


        return Object.freeze({ deactivate: {
            error: () => {
                deactivate('error');
            },
            info: () => {
                deactivate('info');
            },
            success: () => {
                deactivate('success');
            }
        }, error, info, messages, success });

    }
});
