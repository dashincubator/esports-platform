<div class="modal modal--small" data-modifier='black' id='modal-bank-withdraw'>
    <div class="modal-content --background-grey">

        <div class="modal-section">
            <div class="page-header --text-center">
                <h2 class="page-header-title">Withdraw Funds</h2>
                <span class="page-header-subtitle">
                    Available for withdrawal <b class='--text-black'>$<?= $app['auth']->getBankWithdrawable() ?></b>
                </span>
            </div> 

            <form action="<?= $app['route']->uri('account.bank.withdraw.command') ?>" data-submit='processing' method="post">
                <?= $include('@components/field/input/default', [
                    'field' => [
                        'class' => 'field--secondary field--white --margin-top-large --width-full'
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
                        ]
                    ],
                    'field-title' => [
                        'text' => 'Withdraw Amount'
                    ]
                ]) ?>

                <?= $include('@components/field/input/default', [
                    'field' => [
                        'class' => 'field--secondary field--white --margin-top --width-full'
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'name' => 'email',
                            'required' => true
                        ]
                    ],
                    'field-title' => [
                        'text' => 'Paypal Email'
                    ]
                ]) ?>

                <div class="text --margin-top-large --text-small">
                    <div>
                        For more information on withdrawals visit
                        <a class='link link--color link--secondary --inline --text-small' href="<?= $app['route']->uri('legal.deposit-terms') ?>" target="_blank"><b>Withdraw Fees, and other restrictions</b></a>
                    </div>
                </div>

                <button class="button button--large button--secondary --margin-top-large --width-full">Withdraw</button>
            </form>
        </div>

    </div>
</div>
