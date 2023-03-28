/**
 *------------------------------------------------------------------------------
 *
 *  Change File Name On Upload
 *
 */

resolve(['alert', 'directive', 'node'], function(alert, directive, node) {

    'use strict';


    const onchange = function() {
        let button = this.parentElement.getElementsByClassName('button')[0],
            data = new FormData(),
            request = new XMLHttpRequest();

        node.update(button, {
            classname: {
                'button--processing': 'add'
            }
        });

        data.append(this.name, this.files[0]);

        request.onreadystatechange = () => {
            if (request.readyState != 4 || request.status != 200) {
                return;
            }

            let response = JSON.parse(request.response);

            if (response.success) {
                directive.dispatch('upload-update', {}, this);
            }

            alert.messages(response.messages || []);

            if (button) {
                node.update(button, {
                    classname: {
                        'button--processing': 'remove'
                    }
                });
            }
        };
        request.open(this.form.method, this.form.action);
        request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        request.send(data);
    };


    directive.on('upload-onchange', onchange);

});
