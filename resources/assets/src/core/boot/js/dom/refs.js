bind('dom/refs', {
    dependencies: ['emitter', 'node'],
    value: function(emitter, node) {

        'use strict';


        let attribute = 'data-ref',
            cache = new Map();


        function find() {
            return Array.from( document.querySelectorAll(`[${attribute}]`) || [] );
        }

        function ready() {
            cache.clear();

            let elements = find();

            for (let i = 0, n = elements.length; i < n; i++) {
                let element = elements[i],
                    value = element.getAttribute(attribute);

                if (!value) {
                    continue;
                }

                set(value, element);
            }

            node.update(elements, {
                attribute: { [attribute]: false }
            });

            emitter.dispatch('dom.refs.cached');
        }

        function set(value, element) {
            let values = value.split(',').map((v) => {
                return v.trim();
            });

            for (let i = 0, n = values.length; i < n; i++) {
                let id = values[i];

                if (!cache.has(id)) {
                    cache.set(id, []);
                }

                cache.get(id).push(element);
            }
        }


        // Cache On DOM Ready ( Necessary For Tags )
        emitter.on('dom.ready', ready);


        const ref = (id) => {
            let elements = cache.get(id) || [];

            cache.delete(id);

            return elements;
        };


        return ref;

    }
});
