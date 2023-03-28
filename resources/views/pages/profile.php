<?php $layout('@layouts/master/default') ?>

<div class="header-spacer header-spacer--full"></div>

<div class="container">
    <div class="profile-banner" style="background-image: url(<?= $app['upload']->path('user.banner', $data['user.banner']) ?>);">
        <div class="profile-avatar" style="background-image: url(<?= $app['upload']->path('user.avatar', $data['user.avatar']) ?>)">
            <?php if ($data['user.isMembershipActive']): ?>
                <div class="profile-avatar-premium membership-icon membership-icon--large tooltip" data-hover="tooltip">
                    <img class="membership-icon-image" src="/images/membership.svg">
                    <span class="tooltip-content tooltip-content--message tooltip-content--nw">Premium Member</span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class='profile-container'>
        <section class='profile-header'>
            <section class='profile-header-section --button-small-right --button-small-left-800px --text-center-800px'>
                <h1 class="--text-crop-both">
                    <?= $data['user.username'] ?>
                </h1>
            </section>

            <section class="profile-header-section profile-header-section--slug">
                <p class=" --button-small-right --button-small-left-800px --text-center-800px --text-crop-both">
                    @<?= $data['user.slug'] ?>
                </p>
            </section>

            <?php if ($data['user.bio']): ?>
                <section class="profile-header-section profile-header-section--bio">
                    <p class='--text-center-800px --text-crop-both'><?= $data['user.bio'] ?></p>
                </section>
            <?php endif; ?>

            <section class="profile-header-section">
                <div class="group --flex-horizontal-800px --margin-top">
                    <?php $stats = $app['user']->toStatTextListArray($data['ranks'], $data['user']); ?>

                    <?php foreach ($stats as $stat): ?>
                        <div class="group-item --icon-left --margin-top">
                            <div class="icon --absolute-vertical-left">
                                <?= $app['svg']($stat['svg']) ?>
                            </div>

                            <span class="text"><?= $stat['title'] ?>&nbsp;</span>

                            <?php if (is_scalar($stat['text'])): ?>
                                <b class='text'><?= $stat['text'] ?></b>
                            <?php else: ?>
                                <div class="text-group">
                                    <?php foreach ($stat['text'] as $text): ?>
                                        <b class='text'><?= $text ?></b>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="profile-header-section">
                <div class="group --flex-horizontal-800px --margin-top-small">
                    <?php foreach ([
                        $app['user']->getSocialAccountTextList($data['accounts']),
                        $app['user']->getGameAccountTextList($data['accounts'])
                    ] as $items): ?>
                        <?php foreach ($items as $item): ?>
                            <?php
                                $link = $app['config']->has("social.links.{$item['modifier']}");
                                $tag = $link ? 'a' : 'div';
                            ?>

                            <<?= $tag ?>
                                class="button button--circle button--<?= $item['modifier'] ?> group-item <?= $link ? '' : 'tooltip' ?> --margin-top-small"

                                <?php if ($link): ?>
                                    href='<?= $app['config']->get('social.links.' . $item['modifier']) . $item['text'] ?>' target='_blank'
                                <?php else: ?>
                                    data-hover='tooltip'
                                <?php endif; ?>
                            >
                                <div class="icon">
                                    <?= $app['svg']($item['svg']) ?>
                                </div>

                                <?php if ($tag !== 'a'): ?>
                                    <div class="tooltip-content tooltip-content--message tooltip-content--n <?= $link ? '--cursor-pointer' : '' ?>">
                                        <div class="text-list">
                                            <b class="text --flex-horizontal --text-white --width-full">
                                                <?= $item['text'] ?>
                                            </b>
                                            <span class="text --flex-horizontal --text-grey --text-small --width-full">
                                                <?= ucfirst(str_replace('Id', ' ID', $item['title'])) ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </<?= $tag ?>>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </section>
        </section>

        <section class="section section--margin-top">
            <div class="page-header">
                <h2 class="page-header-title --text-crop-top">Arena Stats</h2>
            </div>

            <div class="table table--margin-top">
                <?= $include('@components/leaderboard/table/header', [
                    'fill' => [
                        'text' => 'Arena'
                    ]
                ]) ?>

                <?php if (!count($data['ranks'])): ?>
                    <?= $include('@components/table/row/empty', [
                        'class' => '--button-small-height',
                        'text' => 'No arena ranks found'
                    ]) ?>
                <?php endif; ?>

                <?php foreach ($data['ranks'] as $rank): ?>
                    <?php $section->start('fill.html') ?>
                        <?php
                            $game = $app['game']->get($rank['game']);
                            $platform = $app['platform']->get($game['platform']);
                        ?>

                        <div class="--flex-start">
                            <div class="link--color link--primary text tooltip --game-icons-small-left --game-icons-left-hidden-600px --button-small-height --flex-vertical" data-click="drawer" data-drawer="game-<?= $game['id'] ?>" data-hover="tooltip">
                                <?= $include('@components/game/icons/default', [
                                    'class' => '--absolute-vertical-left --hidden-600px',
                                    'game' => $game,
                                    'small' => true
                                ]) ?>

                                <b class="--text-truncate">
                                    <?= $game['name'] ?>
                                </b>

                                <div class="tooltip-content tooltip-content--message tooltip-content--nw">
                                    Open Arena Menu
                                </div>
                            </div>
                        </div>
                    <?php $section->end() ?>

                    <?= $include('@components/leaderboard/table/row', [
                        'fill' => [
                            'html' => $section('fill.html')
                        ],
                        'table' => $app['leaderboard']->toTableItemArray($rank)
                    ]) ?>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="section section--margin-top">
            <div class="page-header">
                <h2 class="page-header-title">Teams</h2>
            </div>

            <div class="table table--margin-top">
                <?= $include('@components/leaderboard/table/header', [
                    'fill' => [
                        'text' => 'Team'
                    ]
                ]) ?>

                <?php $count = 0; ?>

                <?php foreach ($data['teams'] as $key => $teams): ?>
                    <?php $count += count($teams); ?>

                    <?php foreach ($teams as $team): ?>
                        <?php
                            if (!$app[$key]->has($team[$key])) {
                                continue;
                            }
                        ?>
                        <?php $section->start('fill.html') ?>
                            <?php
                                $event = $app[$key]->get((int) $team[$key]);
                                $game = $app['game']->get((int) $event['game']);
                                $platform = $app['platform']->get((int) $game['platform']);
                            ?>

                            <div class="--flex-vertical --image-large-height --image-large-left --image-left-hidden-600px --width-full">
                                <img class="image image--large --absolute-vertical-left --hidden-600px" src="<?= $app['upload']->path("{$key}.team.avatar", $team['avatar']) ?>">

                                <div class="text-list">
                                    <div class="text">
                                        <a href="<?= $app['route']->uri("{$key}.team", [
                                            'game' => $game['slug'],
                                            'platform' => $platform['slug'],
                                            'team' => $team['slug'],
                                            $key => $event['slug']
                                        ]) ?>" class="link link--color link--primary --inline --text-bold --text-truncate">
                                            <?= $team['name'] ?>
                                        </a>
                                    </div>

                                    <div class='text --text-small'>
                                        <span class="--text-truncate">
                                            <?= $platform['name'] ?>
                                            <?= ucwords(str_replace('-', ' ', $game['slug'])) ?>
                                            <?= $event['name'] ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php $section->end() ?>

                        <?php $prefix = '@components/leaderboard/table' . ($key === 'ladder' && !$event['isMatchFinderRequired'] ? '/score' : ''); ?>

                        <?= $include("{$prefix}/row", [
                            'fill' => [
                                'html' => $section('fill.html')
                            ],
                            'table' => $app['leaderboard']->toTableItemArray($team, ($key === 'ladder' && !$event['isMatchFinderRequired'])),
                            'userprofile' => true
                        ]) ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <?php if (!$count): ?>
                    <?= $include('@components/table/row/empty', ['text' => 'No teams found']) ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>
