<?php $layout('@layouts/master/default') ?>

<div class='header-spacer'></div>

<div class='container --max-width-400px'>
    <section class='section section--margin-top-large'>
        <div class='page-header --text-center'>
            <h1 class='page-header-title'>
                Process Bank Withdraws
            </h1>
            <span class="page-header-subtitle">
                <b class="--text-black"><?= $data['stats.totalWithdraws'] ?></b> Active Withdraws For A Total Value Of <b class="--text-primary">$<?= number_format($data['stats.totalWithdrawAmount'], 2) ?></b>
            </span>
        </div>
    </section>

    <section class='section section--margin-top-large'>
        <div class="card --background-grey --border --border-dashed --border-small --width-full">
            <div class="card-section text-lists">
                <?php if (!$data['withdraw.id']): ?>
                    <div class="text --flex-center --width-full">
                        No Active Bank Withdraw Requests Found
                    </div>
                <?php else: ?>
                    <div class="text-list tooltip --icon-left" data-click='copy' data-copy='amount' data-hover='tooltip'>
                        <div class="icon --absolute-vertical-left">
                            <?= $app['svg']('dollar') ?>
                        </div>

                        <div class="text-group">
                            <div class="text --text-small">
                                Amount
                            </div>
                            <b class="text --text-small">click to copy</b>
                        </div>
                        <div class="text">
                            <b class="--text-truncate">
                                $ <?= number_format($data['withdraw.amount'], 2) ?>
                            </b>
                        </div>

                        <span class="tooltip-content tooltip-content--message tooltip-content--nw">Copy Amount</span>
                    </div>

                    <input class='copy' id="copy-amount" type="number" value='<?= $data['withdraw.amount'] ?>'>

                    <div class="text-list --icon-left">
                        <div class="icon --absolute-vertical-left">
                            <?= $app['svg']('calendar') ?>
                        </div>

                        <div class="text --text-small">Created</div>
                        <div class="text">
                            <b class="--text-truncate">
                                <?= $app['time']->toBankFormat($data['withdraw.createdAt']) ?>
                            </b>
                        </div>
                    </div>

                    <div class="text-list tooltip --icon-left" data-click='copy' data-copy='email' data-hover='tooltip'>
                        <div class="icon --absolute-vertical-left">
                            <?= $app['svg']('web') ?>
                        </div>

                        <div class="text-group">
                            <div class="text --text-small">
                                Paypal Email
                            </div>
                            <b class="text --text-small">click to copy</b>
                        </div>
                        <div class="text">
                            <b class="--text-truncate">
                                <?= $data['withdraw.email'] ?>
                            </b>
                        </div>

                        <span class="tooltip-content tooltip-content--message tooltip-content--nw">Copy Email</span>
                    </div>

                    <input class='copy' id="copy-email" type="email" value='<?= $data['withdraw.email'] ?>'>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php if ($data['withdraw.id']): ?>
        <form action="<?= $app['route']->uri('admincp.bank.withdraw.process.command') ?>" data-submit="processing" method="post">
            <section class="section section--margin-top">
                <?= $include('@components/field/input/default', [
                   'field' => [
                       'class' => 'field--border field--primary --margin-top-large --width-full'
                   ],
                   'field-description' => [
                       'text' => 'Transaction ID is shared with user receiving payment. This will enable tracking of payments by both parties.'
                   ],
                   'field-tag' => [
                       'attributes' => [
                           'name' => 'processorTransactionId',
                           'required' => true,
                           'value' => $data['processorTransactionId']
                       ]
                   ],
                   'field-title' => [
                       'text' => 'Paypal Transaction ID'
                   ]
               ]) ?>

               <input name="id" type="hidden" value="<?= $data['withdraw.id'] ?>">
            </section>

            <section class="section section--margin-top --flex-end">
                <button class="button button--large button--primary button--width --width-full-400px">Save Changes</button>
            </section>
        </form>
    <?php endif; ?>
</div>
