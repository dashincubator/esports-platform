<div class="frame" id="frame-header-bank">
    <div class="header-user-menu-header">
        <?= $include('components/close') ?>

        <b class="text --text-large --text-white">
            Bank Activity
        </b>
    </div>

    <?= $include('components/links', ['links' => [
        [
            'route' => [
                'name' => 'account.bank.deposits'
            ],
            'svg' => 'bank-deposit',
            'text' => 'Deposit History'
        ],
        [
            'route' => [
                'name' => 'account.bank.transactions'
            ],
            'svg' => 'bank-transaction',
            'text' => 'Recent Transactions'
        ],
        [
            'route' => [
                'name' => 'account.bank.withdraws'
            ],
            'svg' => 'bank-withdraw',
            'text' => 'Withdraw History'
        ]
    ]]) ?>
</div>
