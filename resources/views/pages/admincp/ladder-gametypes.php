<?php $layout('@layouts/master/default') ?>

<div class='header-spacer'></div>

<div class='container --max-width-600px'>
    <section class='section section--margin-top-large'>
        <div class='page-header --text-center'>
            <h1 class='page-header-title'>
                Manage Ladder Gametypes
            </h1>
        </div>
    </section>

    <section class='section section--margin-top-large'>
        <?= $include('@components/link/scroller/border-grey', [
            'links' => [
                [
                    'active' => true,
                    'frame' => 'gametypes',
                    'text' => 'Ladder Gametypes'
                ],
                [
                    'href' => $app['route']->uri('admincp.ladder.gametype.create'),
                    'text' => 'Create Ladder Gametype'
                ]
            ],
            'scroller-content-wrapper' => [
                'class' => 'scroller-content-wrapper--center'
            ]
        ]) ?>

        <section class="section section--margin-top">
            <div class="table">
                <div class="table-header table-header--more-right --background-black-500 --text-white">
                    <span class="table-item table-item--width-fill">
                        Active Gametypes
                    </span>
                </div>

                <?php foreach ($data['gametypes'] as $gametype): ?>
                    <?php
                        $game = $app['game']->get($gametype['game']);
                        $platform = $app['platform']->get($game['platform']);
                    ?>

                    <div class="table-row table-row--more-right --background-grey-300 --margin-top-border">
                        <div class="table-item table-item--width-fill">
                            <div class="text-list --game-icons-small-left">
                                <?= $include('@components/game/icons/trigger', [
                                    'class' => '--absolute-vertical-left',
                                    'game' => $game,
                                    'small' => true,
                                    'tooltip' => [
                                        'direction' => 'e'
                                    ]
                                ]) ?>

                                <b class="text">
                                    <?= $gametype['name'] ?>
                                </b>
                                <div class="text --text-small">
                                    <span class="--text-truncate">
                                        <?= $platform['name'] ?> <?= $game['name'] ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <a
                            class="button button--clear button--large button--more button--white tooltip --absolute-vertical-right --border-color-override --border-grey --border-left"
                            data-hover="tooltip"
                            href='<?= $app['route']->uri('admincp.ladder.gametype.edit', ['id' => $gametype['id']]) ?>'
                        >
                            <div class="icon">
                                <?= $app['svg']('settings') ?>
                            </div>

                            <span class="tooltip-content tooltip-content--message tooltip-content--w">Edit Gametype</span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </section>
</div>
