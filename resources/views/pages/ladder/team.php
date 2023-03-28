<?php
    $layout('@layouts/master/default');

    $game = $app['game']->get($data['ladder.game']);
    $platform = $app['platform']->get($game['platform']);
    $slugs = [
        'game' => $game['slug'],
        'ladder' => $data['ladder.slug'],
        'platform' => $platform['slug'],
        'team' => $data['team.slug']
    ];
?>

<?php $section->start('header.buttons.html') ?>
    <?php if ($data['ladder.isMatchFinderRequired']): ?>
        <a class="button button--black button--large --width-half-800px --margin-top" href="<?= $app['route']->uri('ladder.matchfinder', $slugs) ?>">
            Find A Match
        </a>
    <?php endif; ?>
<?php $section->end() ?>

<?php $section->start('important.html') ?>
    <?php if (!$app['auth']->onTeam($data['team.id']) && $data['team.isLocked']): ?>
        <span class='list-item list-item--bulletpoint'>
            Team is locked
        </span>
    <?php endif; ?>

    <?php if (!$data['ladder.isMatchFinderRequired']): ?>
        <?php if ($data['ladder.isOpen']): ?>
            <span class='list-item list-item--bulletpoint'>
                <b>This event is live. Start your matches!</b>
                Scores and stats will be automatically reported, and the team scores will be updated within 15 minutes following the conclusion of each match.
            </span>
            <span class='list-item list-item--bulletpoint'>
                Please note if your team dies and you leave a match early, your scores will be updated, but it may take longer than 15 minutes.
                Match scores are reported by COD servers when the match finishes, not when your team leaves the match and/or dies.
            </span>
        <?php elseif ($data['ladder.isApiGracePeriodOpen']): ?>
            <div class='list-item list-item--bulletpoint'>
                <b>This event is now over.</b>
                The leaderboard is still updating as final matches are reported by COD servers.
                This process can take up to 1 hour, please wait until for <b><?= $app['time']->toLadderFormat($data['ladder.apiGracePeriod']) ?></b> scores to be finalized.
            </div>
        <?php elseif ($data['ladder.isClosed']): ?>
            <div class='list-item list-item--bulletpoint'>
                <b>Team score is final, and all scores are official. </b>
                Prize eligible teams will be paid out shortly.
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($app['auth']->onTeam($data['team.id'])): ?>
        <?php foreach ($data['messages'] as $message): ?>
            <span class='list-item list-item--bulletpoint'><?= $message ?></span>
        <?php endforeach; ?>

        <?php if ($app['auth']->managesTeam($data['team.id']) && $data['ladder.isTeamLockRequired']): ?>
            <?php if ($data['team.isLocked']): ?>
                <span class='list-item list-item--bulletpoint'>
                    Team is locked, this team cannot be deleted
                </span>
            <?php else: ?>
                <?php
                    $app['modal']->add('@components/ladder/modals/lock-team', [
                        'action' => $app['route']->uri('ladder.team.lock.command', $slugs)
                    ]);
                ?>

                <span class='list-item list-item--bulletpoint'>
                    <b>DO NOT FORGET TO LOCK YOUR TEAM.</b>
                    Once your team is finalized please lock your roster.
                    If your team is not locked it will not be eligible for play in this event.
                    <a class='link link--color link--primary --inline' href='<?= $app['route']->uri('faq') ?>'><b>Read More</b></a>
                </span>

                <div class="button button--large button--primary --margin-top --width-full" data-click='modal' data-modal='lock-team'>Lock Team</div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
<?php $section->end() ?>

<?php $section->start('record.fill.html') ?>
    <div class="text --button-small-height --flex-vertical --width-full">
        <a href="<?= $app['route']->uri('ladder', $slugs) ?>" class="link link--color link--primary link--text --inline --text-bold --text-truncate">
            <?= $data['ladder.name'] ?>
        </a>
    </div>
<?php $section->end() ?>

<?php if ($data['ladder.isMatchFinderRequired']): ?>
    <?php
        $matches = [];
        $names = [];

        foreach ($data['gametypes'] as $gametype) {
            $names[$gametype['id']] = $gametype['name'];
        }
    ?>

    <?php foreach($data['matches'] as $match): ?>
        <?php
            $href = $app['route']->uri('ladder.match', array_merge($slugs, ['match' => $match['id']]));
            $result = [
                'class' => '--text-black',
                'text' => 'Active Match'
            ];

            if ($match['winningTeam'] > 0) {
                if ($match['winningTeam'] === $data['team.id']) {
                    $result = [
                        'class' => '--text-green',
                        'text' => 'Won'
                    ];
                }
                else {
                    $result = [
                        'class' => '--text-red',
                        'text' => 'Lost'
                    ];
                }
            }
            elseif ($match['isDisputed']) {
                $result = [
                    'class' => '--text-primary',
                    'text' => 'Disputed'
                ];
            }
        ?>

        <?php $section->start('match.fill.html') ?>
            <div class="text-list">
                <div class="text --width-full">
                    <a class='link link--color link--primary link--underline --flex-bold --flex-vertical --inline --text-bold --text-truncate' href='<?= $href ?>'>
                        <?= $names[$match['gametype']] ?>
                    </a>
                </div>
                <div class="text --width-full">
                    <span class='--text-small --text-truncate'>
                        <?= $app['time']->toMatchFormat($match['startedAt']) ?>
                    </span>
                </div>
            </div>
        <?php $section->end() ?>

        <?php
            $matches[] = [
                'fill' => [
                    'html' => $section('match.fill.html')
                ],
                'href' => $href,
                'result' => $result,
                'startedAt' => $match['startedAt'],
                'winningTeam' => $match['winningTeam']
            ];
        ?>
    <?php endforeach; ?>
<?php else: ?>
    <?php $section->start('warzone.html') ?>
        <section class='section section--margin-top'>
            <div class='page-header'>
                <h2 class='page-header-title --text-crop-top'>Warzone Match History</h2>
                <span class="page-header-subtitle"></span>
            </div>

            <div class='table table--margin-top'>
                <div class="table-header --background-black-500 --border-radius --text-white">
                    <span class="table-item table-item--width-fill">
                        Match Start Time
                    </span>

                    <span class="table-item table-item--width-small tooltip --text-center" data-hover='tooltip'>
                        Damage
                        <span class='tooltip-content tooltip-content--message tooltip-content--n'>
                            Total Damage Dealt In Lobby
                        </span>
                    </span>

                    <span class="table-item table-item--width-small tooltip --text-center" data-hover='tooltip'>
                        Kills
                        <span class='tooltip-content tooltip-content--message tooltip-content--n'>
                            Total Match Kills In Lobby
                        </span>
                    </span>

                    <div class="table-item table-item--width-small tooltip --text-center" data-hover='tooltip'>
                        Place
                        <span class='tooltip-content tooltip-content--message tooltip-content--n'>
                            Warzone Lobby Team Placement
                        </span>
                    </div>

                    <div class="table-item table-item--padding-left team-profile-table-arrow --flex-center">
                        <div class="accordion-arrow icon --text-black"></div>
                    </div>
                </div>

                <div class='team-profile-matches'>
                    <div data-ref='scrollbar,scrollbar:dispatch' data-scroll='scrollbar' data-scrollbar='team-matches'>
                        <?php if (!count($data['matches'])): ?>
                            <?= $include('@components/table/row/empty', [
                                'class' => '--button-small-height',
                                'text' => 'No matches found'
                            ]) ?>
                        <?php endif; ?>

                        <?php foreach($data['matches'] as $id => $matches): ?>
                            <?php
                                $damage = 0;
                                $details = $matches[0];
                                $kills = 0;

                                $time = explode(', ', $app['time']->toMatchFormat($details['data.utcStartSeconds']));
                                $date = $time[0];
                                $time = $time[1];
                            ?>

                            <?php $section->start('matches.temp') ?>
                                <div class='accordion --background-grey --border-dashed --border-small' id='accordion-match-<?= $details['data.matchID'] ?>'>
                                    <?php foreach ($matches as $index => $match): ?>
                                        <?php
                                            $damage += ((int) ($match['data.playerStats.damageDone'] ?? 0));
                                            $kills += ((int) ($match['data.playerStats.kills'] ?? 0));
                                        ?>

                                        <div class="table-row">
                                            <div class="table-item table-item--width-fill">
                                                <?= $match['username'] ?>
                                            </div>

                                            <span class="table-item table-item--width-small --flex-center">
                                                <?= number_format($match['data.playerStats.damageDone']) ?>
                                            </span>

                                            <span class="table-item table-item--width-small --flex-center">
                                                <?= $match['data.playerStats.kills'] ?>
                                            </span>

                                            <span class="table-item table-item--width-small --flex-center">-</span>

                                            <div class="table-item table-item--padding-left team-profile-table-arrow --flex-center">
                                                <div class="accordion-arrow icon --text-black"></div>
                                            </div>
                                        </div>

                                        <div class="border border--dashed border--small --border-grey-500"></div>
                                    <?php endforeach; ?>
                                </div>
                            <?php $section->end() ?>

                            <div class="table-row --background-grey-300 --margin-top-border" data-click='accordion' data-accordion='match-<?= $details['data.matchID'] ?>'>
                                <div class="table-item table-item--width-fill --button-small-height --flex-vertical">
                                    <div class="text-list">
                                        <span class="text --text-small"><?= $date ?></span>
                                        <b class="text"><?= $time ?></b>
                                    </div>
                                </div>

                                <span class="table-item table-item--width-small --flex-center">
                                    <?= number_format($damage) ?>
                                </span>

                                <span class="table-item table-item--width-small --flex-center">
                                    <?= $kills ?>
                                </span>

                                <span class="table-item table-item--width-small --flex-center">
                                    <?= $details['data.playerStats.teamPlacement'] ?><?= is_numeric($details['data.playerStats.teamPlacement']) ? $app['ordinal']($details['data.playerStats.teamPlacement']) : '' ?>
                                </span>

                                <div class="table-item table-item--padding-left team-profile-table-arrow --flex-center">
                                    <div class="accordion-arrow icon --text-black">
                                        <?= $app['svg']('arrow-head') ?>
                                    </div>
                                </div>
                            </div>

                            <?= $section('matches.temp') ?>
                        <?php endforeach; ?>
                    </div>

                    <div class="scrollbar" id='scrollbar-team-matches'></div>
                </div>
            </div>
        </section>
    <?php $section->end() ?>
<?php endif; ?>

<?= $include('@components/event/team/profile', array_merge(compact('game', 'platform', 'slugs'), [
    'header' => [
        'buttons' => [
            'html' => $section('header.buttons.html')
        ]
    ],
    'hide' => [
        'matches' => !$data['ladder.isMatchFinderRequired']
    ],
    'important' => [
        'html' => $section('important.html')
    ],
    'key' => 'ladder',
    'record' => [
        'fill' => [
            'html' => $section('record.fill.html'),
            'text' => ucfirst($data['ladder.type'])
        ],
        'table' => $app['leaderboard']->toTableItemArray($data['team'], !$data['ladder.isMatchFinderRequired'])
    ],
    'matches' => $matches ?? [],
    'warzone' => [
        'html' => $section('warzone.html')
    ]
])) ?>
