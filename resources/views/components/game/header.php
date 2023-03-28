<?php $app['header']->useWhite() ?>

<section class="game-header <?= $data['links'] ? 'game-header--nav' : '' ?>">
    <div class="header-spacer"></div>

    <section class="game-header-content container">
        <?= $include('@components/game/icons/trigger', [
            'game' => $data['game'],
            'tooltip' => [
                'direction' => 'nw'
            ]
        ]) ?>

        <div class="page-header section section--margin-top">
            <h1 class="page-header-title --text-crop-top --text-white">
                <?= $data['title'] ?>
            </h1>

            <?php if ($data['subtitle']): ?>
                <span class="page-header-subtitle --text-crop-bottom --text-grey">
                    <?= $data['subtitle'] ?>
                </span>
            <?php endif; ?>
        </div>

        <?php if ($data['stats']): ?>
            <div class="section section--margin-top">
                <div class="group --margin-top">
                    <?php if ($data['stats.membership']): ?>
                        <div class="membership-icon membership-icon--large tooltip group-item --margin-top" data-hover="tooltip">
                            <img src="/images/membership.svg" class="membership-icon-image">
                            <span class="tooltip-content tooltip-content--e tooltip-content--message">Membership Required</span>
                        </div>
                    <?php endif; ?>

                    <?= $include('@components/text/list', [
                        'class' => 'group-item --margin-top --width-full-400px',
                        'items' => $data['stats.items'],
                        'white' => true
                    ]) ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($data['buttons']): ?>
            <div class="section section--margin-top">
                <div class="group --margin-top-small --width-full">
                    <?= $data['!buttons'] ?>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <?php if ($data['links']): ?>
        <div class="game-header-nav container --width-full-600px">
            <?= $include('@components/link/scroller/white') ?>
        </div>
    <?php endif; ?>

    <?= $data['!html'] ?>

    <div class="game-header-banner">
        <?php
            $banner = $app['upload']->path('game.banner', $data['game.banner']);

            if ($data['ladder.banner']) {
                $banner = $app['upload']->path('ladder.banner', $data['ladder.banner']);
            }
        ?>

        <div class="game-header-banner-image" style='background-image: url(<?= $banner ?>);'></div>
    </div>
</section>
