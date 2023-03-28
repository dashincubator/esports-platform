<div class="modal modal--small" data-modifier='black' id='modal-bank-deposit'>
    <div class="modal-content --background-grey">

        <div class="modal-section">
            <div class="page-header --text-center">
                <h2 class="page-header-title">Deposit Funds</h2>
            </div>

            <form action="<?= $app['route']->uri('account.bank.deposit.command') ?>" class="frame --active" id="frame-bank-paypal" data-submit='processing' method="post">
                <?= $include('@components/field/input/default', [
                    'field' => [
                        'class' => 'field--primary field--white --margin-top-large --width-full'
                    ],
                    'field-description' => [
                        'text' => '$' . number_format($app['config']->get('bank.deposit.minimum'), 2) . ' minimum'
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'min' => $app['config']->get('bank.deposit.minimum'),
                            'name' => 'amount',
                            'required' => true,
                            'step' => '0.01',
                            'type' => 'number'
                        ],
                        'directives' => [
                            'keyup' => 'bank-deposit',
                            'target' => 'bank-deposit-fee'
                        ]
                    ],
                    'field-title' => [
                        'text' => 'Deposit Amount'
                    ]
                ]) ?>

                <div class="text --margin-top-large --text-small">
                    <p>
                        For more information on deposits visit
                        <a class='link link--color link--primary --inline --text-bold --text-small' href="<?= $app['route']->uri('legal.deposit-terms') ?>" target="_blank">Withdraw Fees, and other restrictions</a>
                    </p>
                    <p id='bank-deposit-fee'></p>
                </div>

                <button class="button button--large button--primary --margin-top-large --width-full">
                    <div>
                        Checkout With <b>PayPal</b>
                    </div>
                </button>

                <div class="button button--large button--secondary --margin-top --width-full" data-click='frame' data-frame='bank-dash'>
                    <div>
                        Checkout With <b>Dash</b>
                    </div>
                </div>
            </form>
            
            <div class="frame" id="frame-bank-dash">
                <div id='qr'></div>
                
                <?= $include('@components/field/input/default', [
                    'field' => [
                        'class' => 'field--primary field--white --margin-top-large --width-full'
                    ],
                    'field-description' => [
                        'text' => '$' . number_format($app['config']->get('bank.deposit.minimum'), 2) . ' minimum'
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'min' => $app['config']->get('bank.deposit.minimum'),
                            'name' => 'amount',
                            'required' => true,
                            'step' => '0.01',
                            'type' => 'number'
                        ],
                        'directives' => [
                            'keyup' => 'bank-deposit-dash'
                        ]
                    ],
                    'field-title' => [
                        'text' => 'Deposit Amount'
                    ]
                ]) ?>
            </div>
        </div>

    </div>
</div>
