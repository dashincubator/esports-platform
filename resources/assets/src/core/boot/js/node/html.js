bind('node/html', {
    dependencies: ['render'],
    value: function(render) {

        'use strict';


        let methods = { append, inner, prepend };


        function append(element, html) {
            if (typeof html === 'string' || html instanceof String) {
                html = render.html(html);
            }

            if (html) {
                element.appendChild(html);
            }
        }

        function inner(element, html) {
            let child;

            while (child = element.firstChild) {
                element.removeChild(child);
            }

            append(element, html);
        }

        function prepend(element, html) {
            if (typeof html === 'string' || html instanceof String) {
                html = render.html(html);
            }

            if (html) {
                element.insertBefore(html, element.firstElementChild);
            }
        }


        const html = (elements, obj) => {
            if (!elements) {
                return;
            }
            else if (!Array.isArray(elements)) {
                elements = [elements];
            }

            let directions = typeof obj === 'object' && !obj.ownerDocument;

            for (let i = 0, n = elements.length; i < n; i++) {
                // 'obj' Contains Method Key/Directions
                if (directions) {
                    for (let key in obj) {
                        let method = methods[key];

                        if (!method) {
                            continue;
                        }

                        method(elements[i], obj[key]);
                    }
                }
                // 'obj' Should Be HTML; Use Default Option 'innerHTML'
                else {
                    inner(elements[i], obj);
                }
            }
        };


        return html;

    }
});
