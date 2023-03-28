<?php
    $layout('@layouts/master/default');

    $game = $app['game']->get($data['ladder.game']);
    $platform = $app['platform']->get($game['platform']);
    $slugs = [
        'platform' => $platform['slug'],
        'game' => $game['slug'],
        'ladder' => $data['ladder.slug'],
        'match' => $data['match.id']
    ];
?>

<div class="header-spacer"></div>

<div class='container'>
    <section class='section section--margin-top-large'>
        <div class='page-header'>
            <h1 class='page-header-title --flex-start --width-auto'>
                <div class="--position-relative">
                    <?= ucwords($data['ladder.type']) ?> Match

                    <?php
                        foreach (['isActive', 'isComplete', 'isDisputed'] as $key) {
                            if (!$data["match.{$key}"]) {
                                continue;
                            }

                            $status = strtolower(ltrim($key, 'is'));
                        }
                    ?>

                    <div class="bubble match-status match-status--<?= $status ?> tooltip --border-grey" data-hover='tooltip'>
                        <span class="tooltip-content tooltip-content--message tooltip-content--n">Match is <?= ucfirst($status) ?></span>
                    </div>
                </div>
            </h1>
            <span class='page-header-subtitle'>
                <?= $platform['name'] ?> <?= $game['name'] ?>
                <a href='<?= $app['route']->uri('ladder', $slugs) ?>' class='link link--color link--primary --text-black --inline'>
                    <b><?= $data['ladder.name'] ?></b>
                </a>
                <?= ucwords($data['ladder.type']) ?> Match
            </span>

            <?php
                $links = [
                    [
                        'active' => $app['route']->is('ladder.match'),
                        'frame' => 'match-details',
                        'svg' => 'ticket',
                        'text' => 'Match Details'
                    ]
                ];
                $report = false;

                if ($data['match.isReportable'] && $app['auth']->onLadderTeam($data['ladder.id'])) {
                    $team = $app['auth']->getTeamByladder($data['ladder.id']);

                    if ($app['auth']->managesTeam($team['id'])) {
                        foreach ($data['reports'] as $r) {
                            if ($r['team'] !== $team['id']) {
                                continue;
                            }

                            $report = $r;
                        }
                    }
                }

                if ($report) {
                    $action = $app['route']->uri("ladder.match.report.command", array_merge($slugs, [
                        'report' => $report['id']
                    ]));
                    $options = [];
                    $selected = $report['placement'];

                    for($i = 1; $i <= $data['match.teamsPerMatch']; $i++) {
                        $options[$i] = implode('', [$i, $app['ordinal']($i)]);
                    }

                    $app['modal']->add('@components/event/match/modals/report', compact('action', 'options', 'selected'));

                    $links[] = [
                        'modal' => 'match-report',
                        'svg' => 'ticket',
                        'text' => ($report['isReported'] ? 'Modify Reported Scores' : 'Report Scores')
                    ];
                }

                $links[] = [
                    'href' => $app['route']->uri('ladder.rules', $slugs),
                    'svg' => 'ticket',
                    'target' => '_blank',
                    'text' => ucwords($data['ladder.type']) . ' Rules'
                ];
            ?>
        </div>
    </section>

    <section class='section section--margin-top-large'>
        <?= $include('@components/link/scroller/border-grey', ['links' => $links]) ?>
    </section>

    <section class="section">
        <div class="frame --active" id='match-details'>
            <div class='columns match-columns'>
                <div class='column column--padding-right column--width-fill column--width-full-1200px'>
                    <?php foreach ($data['teams'] as $index => $team): ?>
                        <section class="section section--margin-top<?= $index > 0 ? '-small' : '' ?>">
                            <div class="text-list --image-large-left">
                                <img src="<?= $app['upload']->path('ladder.team.avatar', $team['avatar']) ?>" class="image image--large --absolute-vertical-left">

                                <a href="<?= $app['route']->uri('ladder.team', array_merge($slugs, ['team' => $team['slug']])) ?>" class="link link--color link--primary text --inline --text-bold --text-large">
                                    <?= $team['name'] ?>
                                </a>
                                <span class="text --text-small">Match Roster</span>
                            </div>

                            <?= $include('@components/event/team/table/roster', [
                                'game' => $game,
                                'roster' => $data["rosters.{$team['id']}"]
                            ]) ?>
                        </section>

                        <div class="match-vs">
                            <span class="match-vs-text">vs</span>
                        </div>
                    <?php endforeach; ?>

                    <?php $disbanded = count($data['teams']) !== $data['match.teamsPerMatch'] ?>

                    <?php if ($disbanded !== 0): ?>
                        <div class='card --background-grey --border-dashed --border-radius --border-small --margin-top-large --width-full'>
                            <p class='card-section --text-center'>
                                <?= $disbanded ?> team<?= $disbanded > 1 ? 's' : '' ?> assigned to this match disbanded
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class='column column--padding-left column--width-fixed column--width-full-1200px'>
                    <section class="section section--margin-top">
                        <?= $include('@components/event/match/maps', [
                            'hosts' => $data['match.hosts'],
                            'maps' => $data['match.mapset']
                        ]) ?>

                        <?= $include('@components/event/match/details', [
                            'items' => $app['ladderMatch']->toTextListArray($data['match'], $data['ladder'])
                        ]) ?>

                        <?php if ($data['match.isReportable']): ?>
                            <?php
                                $reports = [];

                                foreach ($data['reports'] as $r) {
                                    if (!$r['isReported']) {
                                        continue;
                                    }

                                    foreach ($data['teams'] as $team) {
                                        if ($team['id'] !== $r['team']) {
                                            continue;
                                        }

                                        $reports[$team['name']] = $r;
                                    }
                                }
                            ?>

                            <?php if (count($reports)): ?>
                                <?php $section->start('html') ?>
                                    <?php foreach ($reports as $name => $r): ?>
                                        <div class="list-item list-item--bulletpoint list-item--margin-top-nested">
                                            <span>
                                                <b class='--text-primary --inline'>
                                                    <?= $name ?>
                                                </b>
                                                reported a <b><?= $r['placement'] . $app['ordinal']($r['placement']) ?> place</b> finish
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php $section->end() ?>

                                <?= $include('@components/event/match/reports', [
                                    'count' => count($reports),
                                    'html' => $section('html')
                                ]) ?>
                            <?php endif; ?>
                        <?php elseif (!$data['match.isComplete']): ?>
                            <div class="card --background-grey --border --border-dashed --border-small --margin-top-small --width-full" id='accordion-match-reports' style='max-height: 999px'>
                                <div class="card-section text-lists">
                                    <p>
                                        Match can be reported after <br>
                                        <b><?= $app['time']->toMatchFormat($data['match.reportableAt']) ?></b>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </section>
                </div>
            </div>
        </div>
    </section>
</div>
