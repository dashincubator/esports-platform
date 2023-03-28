/**
 *------------------------------------------------------------------------------
 *
 *  Bank Deposit Fee Calculator
 *
 */

resolve(['directive', 'dom', 'node'], function(directive, dom, node) {

    'use strict';


    let attribute = 'data-target';


    const calc = function() {
        let field = this,
            value = field.value,
            target = dom.id( field.getAttribute(attribute) );
 
        if (!target) {
            return;
        }

        dom.update(() => {
            let text = '';

            if (value) {
                text = `$${((value * 0.03) + 0.30).toFixed(2)} will be added to the total to pay for the 2.9% + $0.30 fee charged by payment processor`;
            }

            node.text(target, text);
        });
    };


    directive.on('bank-deposit', calc);

});
