/**
 *------------------------------------------------------------------------------
 *
 *  Horizontal Scrolling Using Scroll Wheel
 *
 */

resolve(['directive', 'dom', 'emitter', 'node'], function(directive, dom, emitter, node) {

    'use strict';


    let container = dom.id('site'),
        ref = {
            scroller: 'scroller',
            scrollerCenter: 'scroller-center'
        },
        scroll = {
            counter: 20,
            multiplier: 32,
            threshold: 20
        };


    function increment(e) {
        if (scroll.counter < scroll.threshold) {
            e.preventDefault();
            e.stopPropagation();
        }

        scroll.counter++;
    }

    function mountScroller(elements) {
        let scrollbarWidth = container.offsetWidth - container.clientWidth;

        if (scrollbarWidth === 17) {
            return;
        }

        node.update(elements, {
            style: {
                'margin-bottom': `${-Math.abs(scrollbarWidth)}px`
            }
        });
    }

    function mountScrollerCenter(elements) {
        for (let i = 0, n = elements.length; i < n; i++) {
            let element = elements[i],
                scrollLeftMax = element.scrollWidth - element.clientWidth;

            let scrollLeft = scrollLeftMax / 2;

            if (scrollLeft < 1) {
                continue;
            }

            node.update(element, {
                attribute: { scrollLeft}
            });
        }
    }


    const mount = () => {
        mountScroller(dom.refs(ref.scroller));
        mountScrollerCenter(dom.refs(ref.scrollerCenter));
    };

    const wheel = function(e) {
        let scrollLeft = this.scrollLeft - (Math.max(-1, Math.min(1, (- e.deltaY / 3))) * scroll.multiplier),
            scrollLeftMax = this.scrollWidth - this.clientWidth;

        // Scroll Up ( Left )
        if ((- e.deltaY / 3) === 1) {
            if (this.scrollLeft < 1) {
                return increment(e);
            }

            if (scrollLeft < 1) {
                scrollLeft = 0;
            }
        }
        // Scroll Down ( Right )
        else {
            if (this.scrollLeft > (scrollLeftMax - 1)) {
                return increment(e);
            }

            if (scrollLeft > scrollLeftMax) {
                scrollLeft = scrollLeftMax;
            }
        }

        // Valid Scroll Reset Counter
        scroll.counter = 0;

        e.preventDefault();
        e.stopPropagation();

        node.update(this, {
            attribute: { scrollLeft }
        });
    };


    directive.on('scroller', wheel);
    emitter.on('components.mount', mount);

});
