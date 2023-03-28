/**
 *------------------------------------------------------------------------------
 *
 *  Scroll To Element On Page
 *
 */

resolve(['directive', 'dom'], function(directive, dom) {

    'use strict';


    let attribute = 'data-scrollto',
        container = dom.id('site'),
        id = (k) => `scrollto-${k}`;


    if (!container) {
        return;
    }


    let maxScroll = container.scrollHeight - container.clientHeight,
        offset = 0;


    function getOffsetTop(element) {
        let distance = 0;

        if (element.offsetParent) {
            do {
                distance += element.offsetTop;
                element   = element.offsetParent;
            } while (element);
        }

        return (distance < 0) ? 0 : distance;
    }

    function scroll() {
        let difference = offset - container.scrollTop,
            shift = 100;

        if (Math.abs(difference) < shift) {
            shift = Math.abs(difference);
        }

        if (difference > 0) {
            if ((container.scrollTop + shift) > maxScroll) {
                return;
            }

            container.scrollTop += shift;
        }
        else if (difference < 0) {
            if ((container.scrollTop - shift) < 0) {
                return;
            }

            container.scrollTop -= shift;
        }

        if (difference !== 0) {
            setTimeout(scroll, 10);
        }
    }


    const scrollTo = function(e) {
        let target = dom.id( id(this.getAttribute(attribute)) );

        if (!target) {
            return;
        }

        e.preventDefault();

        offset = getOffsetTop(target);

        scroll();
    };


    directive.on('scrollto', scrollTo);

});
