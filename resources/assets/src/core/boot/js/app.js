const { app, bind, resolve } = (function() {

    'use strict';


    const bindings = (function() {

        let bindings = new Map();


        const get = (name) => {
            let binding = bindings.get(name);

            if (!binding) {
                console.error(`Invalid Container Binding: Namespace Not Found '${name}'`);
            }

            return binding;
        };

        const set = (name, opts) => {
            let { dependencies, singleton, value } = opts;

            // We Don't Need/Want Overriding Ability
            if (bindings.has(name)) {
                return;
            }

            // Value Is Being Provided ( Obj, Fn, Primitive )
            if (!dependencies && !singleton && !value) {
                value = opts;
            }

            dependencies = dependencies || [];

            if (typeof singleton === 'boolean' && singleton === false) {
                singleton = false;
            }

            singleton = true;

            if (!value) {
                console.error(`Invalid Container Binding: '${name}' Is Missing The Value Property`);
            }

            bindings.set(name, Object.freeze({ dependencies, singleton, value }));
        };


        return Object.freeze({ get, set });

    })();


    const resolver = (function(bindings) {

        let resolved = new Map();


        function build(dependencies, fn) {
            if (!dependencies) {
                return fn();
            }
            else if (!Array.isArray(dependencies)) {
                dependencies = [dependencies];
            }

            let values = [];

            for (let i = 0, n = dependencies.length; i < n; i++) {
                values.push(handle(dependencies[i]));
            }

            return fn(...values);
        }

        function handle(namespace, fn) {
            if (fn) {
                return build(namespace, fn);
            }
            else if (typeof namespace === 'string' && resolved.has(namespace)) {
                return resolved.get(namespace);
            }

            let { dependencies, singleton, value } = bindings.get(namespace);

            if (typeof value === 'function') {
                value = build(dependencies, value);
            }

            if (singleton) {
                resolved.set(namespace, value);
            }

            return value;
        }


        const resolve = (namespace, fn) => {
            return handle(namespace, fn);
        };


        return Object.freeze({ resolve });

    })(bindings);


    let boot = false,
        deferred = [];


    const app = {
        start: () => {
            if (boot) {
                return;
            }

            boot = true;

            for (let i = 0, n = deferred.length; i < n; i++) {
                let { namespace, fn } = deferred[i];

                resolver.resolve(namespace, fn);
            }

            deferred = [];
        }
    };

    const bind = (name, opts) => {
        bindings.set(name, opts);
    };

    const resolve = (namespace, fn) => {
        if (!boot) {
            return deferred.push({ namespace, fn });
        }

        return resolver.resolve(namespace, fn);
    };


    return Object.freeze({ app, bind, resolve });

})();
