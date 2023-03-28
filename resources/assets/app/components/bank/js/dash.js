/**
 *------------------------------------------------------------------------------
 *
 *  Dash Deposit
 *
 */

resolve(['directive', 'dom', 'node'], function(directive, dom, node) {

    'use strict';


    let data = localStorage.getItem('data') || "{}",
        previous,
        qr = document.getElementById('qr');
        
        
    async function dash() {
        let field = this,
            value = field.value;
            
        data = JSON.parse( data );
    
        if (!data || !previous || value != previous || (data.expires || 0) <= (Date.now() / 1000)) {
            data = await window.bip70.create({
                pay: [
                    {
                        amount: value,
                        // Replace With Site Public Address
                        address: 'yNgkWeuCEzyH8J7PBB9vYQGspsa81Ajfrs'
                    }
                ]
            });
    
            localStorage.setItem('data', JSON.stringify(data));
        }
    
        qr.src = data.qr;
    
        window.bip70.poll(data.poll, `/dash-deposit/${value}`, true);
    }
    
    
    directive.on('bank-deposit-dash', dash);

});
