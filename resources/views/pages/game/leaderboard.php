<?php $layout('@layouts/master/default') ?>

<?php $section->start('html') ?>
    <div class='container'>
        <div class='columns'>
            <div class='column column--padding-right column--width-fill column--width-full-1200px'>
                <div class='table'>
                    <?= $include('@components/leaderboard/table/header', [
                        'fill' => [
                            'text' => 'User'
                        ]
                    ]) ?>
                </div>
            </div>

            <div class='column column--width-fixed --hidden-1200px'></div>
        </div>
    </div>
<?php $section->end() ?>

<?= $include('@components/game/header', [
    'html' => $section('html'),
    'stats' => [
        'items' => $app['game']->toTextListArray($data['game'])
    ],
    'subtitle' => 'Arena Leaderboard',
    'title' => "{$data['game.name']} Leaderboard",
]) ?>

<div class="container">
    <div class="columns">
        <div class="column column--padding-right column--width-fill column--width-full-1200px">
            <div class="table">
                <?php if (!count($data['ranks'])): ?>
                    <?= $include('@components/table/row/empty', [
                        'text' => 'No ranked users found'
                    ]) ?>
                <?php endif; ?>

                <?php foreach ($data['ranks'] as $row): ?>
                    <?php $section->start('fill.html') ?>
                        <?php $user = $data['users.' . $row['user']] ?>

                        <div class="text --flex-vertical --image-large-left --image-large-height --image-left-hidden-400px --membership-icon-right --membership-icon-right-hidden-400px">
                            <img class="table-item-image image image--large --absolute-vertical-left --hidden-400px" src="<?= $app['upload']->path('user.avatar', $user['avatar']) ?>" />

                            <a class="link link--color link--primary link--text --inline --text-bold --text-truncate" href="<?= $app['route']->uri('profile', ['slug' => $user['slug']]) ?>">
                                <?= $user['username'] ?>
                            </a>

                            <?php if ($user['isMembershipActive']): ?>
                                <div class="membership-icon tooltip --hidden-400px --absolute-vertical-right" data-hover="tooltip">
                                    <img src="/images/membership.svg" class="membership-icon-image">
                                    <span class="tooltip-content tooltip-content--message tooltip-content--w">Premium Member</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php $section->end() ?>

                    <?= $include('@components/leaderboard/table/row', [
                        'class' => $app['leaderboard']->createTableRowModifiers($row),
                        'fill' => [
                            'html' => $section('fill.html')
                        ],
                        'table' => $app['leaderboard']->toTableItemArray($row)
                    ]) ?>
                <?php endforeach; ?>
            </div>

            <?= $include('@components/pagination/default', [
                'route' => [
                    'uri' => $app['route']->getName(),
                    'variables' => [
                        'game' => $data['game.slug'],
                        'platform' => $app['platform']->get($data['game.platform'])['slug']
                    ]
                ],
                'subtext' => "<b>{$app['config']->get('game.rating.period')} matches</b> must be played to receive a rating in this arena",
                'text' => 'ranked users'
            ]) ?>
        </div>

        <div class="column column--padding-left column--width-fixed column--width-full-1200px">
            <section class="section section--margin-top">
                <?php foreach ($app['config']->get('pages.game.leaderboard.sidebar') as $index => $section): ?>
                    <div class="button button--border-small button--tab --background-grey --border-dashed --border-default <?= ($index > 0) ? '--margin-top-small' : '' ?> --width-full" data-click='accordion' data-accordion='sidebar-<?= $index ?>'>
                        <div class="--icon-right --width-full">
                            <b><?= $section['title'] ?></b>

                            <div class="accordion-arrow icon --absolute-vertical-right">
                                <?= $app['svg']('arrow-head') ?>
                            </div>
                        </div>
                    </div>

                    <div class="accordion card --background-grey --border --border-dashed --border-small" id='accordion-sidebar-<?= $index ?>'>
                        <div class="card-section">
                            <?= $section['!html'] ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
        </div>
    </div>
</div>
