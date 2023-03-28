bind('dom/id', {
    dependencies: ['emitter'],
    value: function(emitter) {

        'use strict';


        let cache = new Map();


        function find(key) {
            return document.getElementById(key);
        }

        function ready() {
            cache.clear();
        }


        // Flush Cache On DOM Ready ( Necessary For Tags )
        emitter.on('dom.ready', ready);


        const id = (key) => {
            if (!key) {
                return;
            }

            if (!cache.has(key)) {
                cache.set(key, find(key));
            }

            return cache.get(key);
        };


        return id;

    }
});
