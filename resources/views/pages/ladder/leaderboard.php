<?php
    $layout('@layouts/ladder');

    $game = $app['game']->get($data['ladder.game']);
    $slugs = [
        'game' => $game['slug'],
        'ladder' => $data['ladder.slug'],
        'platform' => $app['platform']->get($game['platform'])['slug']
    ];

    $prefix = '@components/leaderboard/table' . ($data['ladder.isMatchFinderRequired'] ? '' : '/score');
?>

<div class="frames">
    <div class="frame --active" id="frame-leaderboard">
        <div class="container">
            <div class="columns">
                <div class="column column--padding-right column--width-fill column--width-full-1200px">
                    <section class="section section--margin-top">
                        <div class="table">
                            <?= $include("{$prefix}/header", [
                                'fill' => [
                                    'text' => 'Team'
                                ]
                            ]) ?>

                            <?php if (!count($data['teams'])): ?>
                                <?= $include('@components/table/row/empty', [
                                    'text' => 'No teams found'
                                ]) ?>
                            <?php endif; ?>

                            <?php foreach ($data['teams'] as $row): ?>
                                <?php $section->start('fill.html') ?>
                                    <?php $user = $data['users.' . $row['user']] ?>

                                    <div class="text --flex-vertical --button-badge-right --image-large-left --image-large-height --image-left-hidden-400px">
                                        <?php if ($data['ladder.isLeague']): ?>
                                            <?php $eligible = $row['isLocked']; ?>

                                            <div class="bubble bubble--<?= $eligible ? 'green' : 'red' ?> bubble--top-left tooltip --border-grey-300" data-hover='tooltip'>
                                                <span class="tooltip-content tooltip-content--message tooltip-content--e">
                                                    Team <?= $eligible ? 'locked' : 'is not locked' ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>

                                        <img class="table-item-image image image--large --absolute-vertical-left --hidden-400px" src="<?= $app['upload']->path('ladder.team.avatar', $row['avatar']) ?>" />

                                        <a class="link link--color link--primary link--text --inline --text-bold --text-truncate" href="<?= $app['route']->uri('ladder.team', $slugs + ['team' => $row['slug']]) ?>">
                                            <?= $row['name'] ?>
                                        </a>
                                    </div>
                                <?php $section->end() ?>

                                <?= $include("{$prefix}/row", [
                                    'class' => $app['leaderboard']->createTableRowModifiers($row, (!$data['ladder.isMatchFinderRequired'])),
                                    'fill' => [
                                        'html' => $section('fill.html')
                                    ],
                                    'table' => $app['leaderboard']->toTableItemArray($row, (!$data['ladder.isMatchFinderRequired']))
                                ]) ?>
                            <?php endforeach; ?>
                        </div>

                        <?= $include('@components/pagination/default', [
                            'route' => [
                                'uri' => $app['route']->getName(),
                                'variables' => $slugs
                            ],
                            'subtext' => (
                                $data['ladder.isMatchFinderRequired']
                                    ? "<b>{$app['config']->get('game.rating.period')} matches</b> must be played to receive a rating in this {$data['ladder.type']}"
                                    : ""
                            ),
                            'text' => 'teams'
                        ]) ?>
                    </section>
                </div>

                <div class="column column--padding-left column--width-fixed column--width-full-1200px">
                    <?php
                        $key = 'pages.ladder.leaderboard.sidebar.';

                        if ($data['ladder.format']) {
                            $key .= $game['slug'] . ($data['ladder.firstToScore'] ? '-race' : '');
                        }
                        else {
                            $key .= 'matchfinder';
                        }
                    ?>

                    <?php if ($app['config']->has($key)): ?>
                        <section class="section section--margin-top">
                            <?php foreach ($app['config']->get($key) as $index => $section): ?>
                                <div class="button button--border-small button--tab --background-grey --border-dashed --border-default --line-height-400 <?= ($index > 0) ? '--margin-top-small' : '' ?> --width-full" data-click='accordion' data-accordion='sidebar-<?= $index ?>'>
                                    <div class="--icon-right --width-full">
                                        <b class='--text-crop-both'><?= $section['title'] ?></b>

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
                    <?php endif; ?>

                    <?php if (!$data['ladder.isMatchFinderRequired']): ?>
                        <section class="section section--margin-top-small">
                            <div class="card --background-grey --border-dashed --border-small --width-full">
                                <div class="card-section">
                                    <div class="page-header">
                                        <h4 class="page-header-title --text-red">IMPORTANT!</h4>
                                    </div>

                                    <div class="list --margin-top-small">
                                        <?php if ($data['ladder.isOpen']): ?>
                                            <span class='list-item list-item--bulletpoint'>
                                                <b>This event is live. Start your matches!</b>
                                                Scores and stats will be automatically reported, and the team scores will be updated within 15 minutes following the conclusion of each match.
                                            </span>
                                            <span class='list-item list-item--bulletpoint'>
                                                <b>Please note if your team dies and you leave a match early!</b>
                                                Match scores are reported by COD servers when the match finishes, not when your team leaves the match and/or dies.
                                            </span>
                                        <?php elseif ($data['ladder.isApiGracePeriodOpen']): ?>
                                            <div class='list-item list-item--bulletpoint'>
                                                <b>This event is now over!</b>
                                                The leaderboard is still updating as final matches are reported by COD servers.
                                                This process can take up to 1 hour, please wait until <b><?= $app['time']->toLadderFormat($data['ladder.apiGracePeriod']) ?></b> for scores to be finalized.
                                            </div>
                                        <?php elseif ($data['ladder.isClosed']): ?>
                                            <div class='list-item list-item--bulletpoint'>
                                                <b>Team score is final, and all scores are official.</b>
                                                Prize eligible teams will be paid out shortly.
                                            </div>
                                        <?php else: ?>
                                            <div class='list-item list-item--bulletpoint'>
                                                <b>This event has not started yet.</b>
                                                Create your team, checkin and get ready to compete!
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </section>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="frame" id="frame-prizes">
        <?php
            $prizes = [];

            if ($data['ladder.entryFee'] && $data['ladder.entryFeePrizes'] && ($data['ladder.totalLockedTeams'] > 0 || $data['ladder.prizePool'] > 0)) {
                $replace = [];
                $total = $data['ladder.prizePool'] + ($data['ladder.totalLockedMembers'] * $data['ladder.entryFee']);

                foreach ($data['ladder.entryFeePrizes'] as $key => $decimal) {
                    $replace['{' . $key . '}'] = '$' . number_format($total * $decimal);
                }
            }
            else {
                $replace = [];

                foreach ($data['ladder.entryFeePrizes'] as $key => $decimal) {
                    $replace['{' . $key . '}'] = '';
                }
            }

            foreach ($data['ladder.prizes'] as $place => $items) {
                $prizes[$place] = [];

                foreach ($items as $item) {
                    $prizes[$place][] = str_replace(array_keys($replace), array_values($replace), $item);
                }
            }

            if (!count($prizes)) {
                $prizes = $data['ladder.prizes'];
            }
        ?>

        <?= $include('@components/event/prizes', [
            'prizes' => $prizes,
            'subtitle' => $data['ladder.prizesAdjusted']
                ? 'Final prize list will be adjusted based on the total number of ranked teams'
                : 'Prizes are guaranteed once 2 teams receive ranks'
        ]) ?>
    </div>

    <div class="frame" id="frame-rules">
        <?= $include('@components/event/rules') ?>
    </div>

    <div class="frame" id="frame-faq">
        <?= $include('@components/event/faq'); ?>
    </div>
</div>
