<?php $layout('@layouts/master/default') ?>

<div class="header-spacer"></div>

<div class="container container--width-1000px">
    <section class='section section--margin-top-large --flex-horizontal'>
        <div class="page-header --max-width-600px --text-center">
            <h1 class="page-header-title">Bank Activity</h1>

            <span class="page-header-subtitle">
                Browse deposit history, recent transactions, withdraw history, and manage pending transactions.
            </span>
        </div>
    </section>

    <section class='section section--margin-top-large'>
        <?= $include('@components/link/scroller/border-grey', [
            'links' => [
                [
                    'active' => $app['route']->is('account.bank.deposits'),
                    'href' => $app['route']->uri('account.bank.deposits'),
                    'svg' => 'bank-deposit',
                    'text' => 'Deposit History'
                ],
                [
                    'active' => $app['route']->is('account.bank.transactions'),
                    'href' => $app['route']->uri('account.bank.transactions'),
                    'svg' => 'bank-transaction',
                    'text' => 'Recent Transactions'
                ],
                [
                    'active' => $app['route']->is('account.bank.withdraws'),
                    'href' => $app['route']->uri('account.bank.withdraws'),
                    'svg' => 'bank-withdraw',
                    'text' => 'Withdraw History'
                ]
            ],
            'scroller-content-wrapper' => [
                'class' => 'scroller-content-wrapper--center'
            ]
        ]) ?>
    </section>

    <section class="section section--margin-top">
        <div class="table">
            <div class="table-header --background-black-500 --text-white">
                <div class="table-item --button-badge-width"></div>

                <?php if (in_array($data['type'], ['deposits', 'withdraws'])): ?>
                    <span class="table-item table-item--padding-left table-item--width-fill">Paypal Email</span>
                    <span class="table-item table-item--width-large --hidden-600px">Paypal Transaction ID</span>
                <?php else: ?>
                    <span class="table-item table-item--padding-left table-item--width-fill">Memo</span>
                <?php endif; ?>

                <span class="table-item table-item--width-large --hidden-600px --text-right">Amount</span>
            </div>

            <?php if (!count($data[$data['type']])): ?>
                <?= $include('@components/table/row/empty', [
                    'class' => '--button-small-height',
                    'text' => 'No ' . trim($data['type'], 's') . ' history found'
                ]) ?>
            <?php endif; ?>

            <?php foreach ($data[$data['type']] as $row): ?>
                <?php $table = $app['bank']->{'to' . ucfirst($data['type']) . 'TableItemArray'}($row); ?>

                <div class="table-row table-row--more-right-600px --background-grey-300 --margin-top-border">
                    <div class="table-item --button-badge-width">
                        <?php $key = in_array($data['type'], ['deposits', 'withdraws']) ? 'processedAt' : 'refundedAt'; ?>

                        <div class="button button--<?= $row[$key] ? 'green' : 'black' ?> button--circle button--badge tooltip --absolute-center" data-hover="tooltip">
                            <div class="icon icon--badge">
                                <?= $app['svg']($row[$key] ? 'check' : 'minus') ?>
                            </div>

                            <span class="tooltip-content tooltip-content--message tooltip-content--e">
                                <?= $table['status'] ?>
                            </span>
                        </div>
                    </div>

                    <?php if (in_array($data['type'], ['deposits', 'withdraws'])): ?>
                        <div class="table-item table-item--padding-left table-item--width-fill text-list">
                            <div class="text">
                                <b class="--text-truncate"><?= $table['email'] ?></b>
                            </div>
                            <div class="text">
                                <span class="--text-small --text-truncate"><?= $table['date'] ?></span>
                            </div>
                        </div>

                        <div class="table-item table-item--width-large --flex-vertical --hidden-600px">
                            <span class="--text-truncate">
                                <?= $table['transactionId'] ?>
                            </span>
                        </div>

                        <div class="table-item table-item--width-large text-list --hidden-600px">
                            <b class="text --flex-end --text-green --width-full">
                                <?= $data['type'] === 'deposits' ? '+' : '-' ?> <?= $table['amount'] ?>
                            </b>
                            <div class="text --flex-end">
                                <div class="tooltip <?= $table['fee'] !== '-' ? '--text-red' : '' ?> --cursor-pointer --text-small" data-hover='tooltip'>
                                    <?= $table['fee'] !== '-' ? '-' : '' ?> <?= $table['fee'] ?>

                                    <span class="tooltip-content tooltip-content--message tooltip-content--se">
                                        Fees charged during payment processing
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="table-item table-item--padding-left table-item--width-fill text-list">
                            <div class="text">
                                <b>
                                    <?= $table['memo'] ?>
                                </b>
                            </div>
                            <span class="text">
                                <span class="--text-small --text-truncate">
                                    <?= $table['date'] ?>
                                </span>
                            </span>
                        </div>

                        <div class="table-item table-item--width-large text-list --hidden-600px">
                            <div class="text --flex-end">
                                <b class="<?= $row['isCharge'] ? '--text-red' : '--text-green' ?> <?= $row['refundedAt'] ? '--text-linethrough' : '' ?>">
                                    <?= $row['isCharge'] ? '-' : '+' ?> <?= $table['amount'] ?>
                                </b>
                            </div>
                            <div class="text --flex-end">
                                <span class='--text-small <?= $row['refundedAt'] ? '--text-linethrough' : '' ?> <?= $table['fee'] !== '-' ? '--text-red' : '' ?>'>
                                    <?= $table['fee'] !== '-' ? '-' : '' ?> <?= $table['fee'] ?>
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="button button--clear button--large button--more button--white tooltip --absolute-vertical-right --border-color-override --border-grey --border-left --visible-600px" data-hover="tooltip">
                        <div class="icon">
                            <?= $app['svg']('menu/more') ?>
                        </div>

                        <div class="tooltip-content tooltip-content--ne tooltip-content--table --background-black-500 --cursor-default">
                            <div class="scroller">
                                <div class='scroller-content' data-ref='scroller' data-wheel='scroller'>
                                    <div class="table table--tooltip">
                                        <div class='table-header --background-black-500 --text-white'>

                                            <?php if (in_array($data['type'], ['deposits', 'withdraws'])): ?>
                                                <span class="table-item table-item--width-large">Paypal Transaction ID</span>
                                            <?php endif; ?>

                                            <span class="table-item table-item--width-large --text-right">Amount</span>
                                        </div>
                                        <div class="table-row --background-white --margin-top-border">
                                            <div class="table-item table-item--width-large --flex-vertical">
                                                <span class="--text-truncate">
                                                    <?= $table['transactionId'] ?>
                                                </span>
                                            </div>

                                            <?php if (in_array($data['type'], ['deposits', 'withdraws'])): ?>
                                                <div class="table-item table-item--width-large text-list">
                                                    <b class="text --flex-end --text-green --width-full">
                                                        <?= $data['type'] === 'deposits' ? '+' : '-' ?> <?= $table['amount'] ?>
                                                    </b>
                                                    <div class="text --flex-end">
                                                        <div class="tooltip <?= $table['fee'] !== '-' ? '--text-red' : '' ?> --cursor-pointer --text-small" data-hover='tooltip'>
                                                            <?= $table['fee'] !== '-' ? '-' : '' ?> <?= $table['fee'] ?>

                                                            <span class="tooltip-content tooltip-content--message tooltip-content--ne">
                                                                Fees charged during payment processing
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="table-item table-item--width-large text-list">
                                                    <div class="text --flex-end">
                                                        <b class="<?= $row['isCharge'] ? '--text-red' : '--text-green' ?> <?= $row['refundedAt'] ? '--text-linethrough' : '' ?>">
                                                            <?= $row['isCharge'] ? '-' : '+' ?> <?= $table['amount'] ?>
                                                        </b>
                                                    </div>
                                                    <div class="text --flex-end">
                                                        <span class='--text-small <?= $row['refundedAt'] ? '--text-linethrough' : '' ?> <?= $table['fee'] !== '-' ? '--text-red' : '' ?>'>
                                                            <?= $table['fee'] !== '-' ? '-' : '' ?> <?= $table['fee'] ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?= $include('@components/pagination/default', [
            'route' => [
                'uri' => $app['route']->getName()
            ],
            'text' => "bank {$data['type']}"
        ]) ?>
    </section>
</div>
