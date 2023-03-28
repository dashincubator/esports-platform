/**
 *------------------------------------------------------------------------------
 *
 *  Scrollbar Replacement
 *
 */

resolve(['dom', 'directive', 'emitter', 'node'], function(dom, directive, emitter, node) {

    'use strict';


    let attribute = 'data-scrollbar',
        container = dom.id('site'),
        id = (k) => `scrollbar-${k}`,
        modifier = 'scrollbar--hidden',
        ref = {
            container: 'scrollbar',
            dispatch: 'scrollbar:dispatch'
        };


    const mount = () => {
        let containers = dom.refs(ref.container);

        if (containers.length) {
            dom.update(() => {
                let scrollbarWidth = container.offsetWidth - container.clientWidth;

                if (scrollbarWidth === 17) {
                    return;
                }

                node.style(containers, { 'width': `calc(100% + ${scrollbarWidth}px)` });
            });
        }

        let dispatch = dom.refs(ref.dispatch);

        dom.update(() => {
            for (let i = 0, n = dispatch.length; i < n; i++) {
                (scrollbar.bind(dispatch[i]))();
            }
        });
    };

    const scrollbar = function() {
        let scrollbar = dom.id( id(this.getAttribute(attribute)) );

        if (!scrollbar) {
            return;
        }

        let height = (this.clientHeight / this.scrollHeight) * 100,
            translate = `translate3d(0, ${(this.scrollTop / this.clientHeight) * 100}%, 0)`;

        node.update(scrollbar, {
            classname: {
                [modifier]: height >= 100
            },
            style: {
                '-webkit-transform': translate,
                '-ms-transform': translate,
                'transform': translate,
                'height': `${height}%`
            }
        });
    };


    directive.on('scrollbar', scrollbar);
    emitter.on('components.mount', mount);

});
