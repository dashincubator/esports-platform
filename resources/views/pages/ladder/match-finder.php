<?php
    $layout('@layouts/ladder');

    $game = $app['game']->get($data['ladder.game']);
    $platform = $app['platform']->get($game['platform']);
    $slugs = [
        'game' => $game['slug'],
        'ladder' => $data['ladder.slug'],
        'platform' => $platform['slug']
    ];

    $team = false;

    if ($app['auth']->onLadderTeam($data['ladder.id'])) {
        $team = $app['auth']->getTeamByLadder($data['ladder.id']);

        $app['modal']->add('@components/ladder/modals/match-accept', compact('slugs', 'team'));
    }
?>

<div class="container">
    <div class="columns">
        <div class="column column--padding-right column--width-fill column--width-full-1200px">
            <section class="section section--margin-top">
                <div class="table">
                    <div class="table-header --background-black-500 --text-white">
                        <span class="table-item table-item--width-full">Hover Over Match For Details</span>
                    </div>

                    <?php if (!count($data['matches'])): ?>
                        <?= $include('@components/table/row/empty', [
                            'text' => 'No active matches found'
                        ]) ?>
                    <?php endif; ?>

                    <?php
                        $canAccept = true;
                        $groups = [];
                        $names = [];

                        foreach (($data['matches'] ?? []) as $match) {
                            $groups[$match['playersPerTeam']][] = $match;
                        }

                        foreach ($data['gametypes'] as $gametype) {
                            $names[$gametype['id']] = $gametype['name'];
                        }
                    ?>

                    <?php foreach ($groups as $playersPerTeam => $matches): ?>
                        <b class="table-row --background-grey --margin-top-border --text-small">
                            Requires <?= $playersPerTeam ?> Player(s)
                        </b>

                        <?php foreach ($matches as $match): ?>
                            <?php
                                $json = json_encode([
                                    'gametype' => $names[$match['gametype']],
                                    'id' => $match['id'],
                                    'playersPerTeam' => $match['playersPerTeam']
                                ]);
                            ?>

                            <div class="ladder-matchfinder-match <?= $team && $team['id'] === $match['team'] ? 'ladder-matchfinder-match--highlight' : '' ?> table-row table-row--border-black --background-grey-300 --margin-top-border tooltip" data-hover="tooltip">
                                <div class="table-item table-item--width-fill --flex-vertical">
                                    <div class='text-list --icon-small-left --width-full'>
                                        <div class='ladder-matchfinder-match-more --absolute-vertical-left'>
                                            <div class="icon icon--rotate270">
                                                <?= $app['svg']('menu/more') ?>
                                            </div>
                                        </div>

                                        <div class="text --width-full">
                                            <b class='--text-truncate'>
                                                <?= $names[$match['gametype']] ?>
                                            </b>
                                        </div>
                                        <div class="text-group">
                                            <div class="text">
                                                <b class='--text-small'>
                                                    Best of <?= $match['bestOf'] ?>
                                                </b>
                                            </div>

                                            <?php if ($match['wager'] > 0): ?>
                                                <div class="text">
                                                    <b class='--text-small --text-primary'>
                                                        $<?= $match['wager'] ?> Wager Per Player
                                                    </b>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-item table-item--width --flex-center --hidden-400px">
                                    <?php if ($team): ?>
                                        <?php if ($team['id'] === $match['team']): ?>
                                            <?php $canAccept = false; ?>

                                            <form action="<?= $app['route']->uri('ladder.match.cancel.command', $slugs) ?>" data-submit='processing' method="post">
                                                <input type="hidden" name="id" value="<?= $match['id'] ?>">
                                                <button class="button button--black --width-full">Cancel</button>
                                            </form>
                                        <?php elseif ($canAccept): ?>
                                            <div class="button button--primary --width-full" data-click='matchfinder-accept,modal' data-json='<?= $json ?>' data-modal='match-accept'>Accept</div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>

                                <div class="tooltip-content --background-white tooltip-content--nw">
                                    <?= $include('@components/tooltip/section', [
                                        'items' => $app['ladderMatch']->toTextListArrayShort($match),
                                        'order' => ['title', 'text']
                                    ]) ?>

                                    <div class="tooltip-section --border-small --border-top --border-grey --visible-400px">
                                        <?php if ($team): ?>
                                            <?php if ($team['id'] === $match['team']): ?>
                                                <form action="<?= $app['route']->uri('ladder.match.cancel.command', $slugs) ?>" data-submit='processing' method="post">
                                                    <input type="hidden" name="id" value="<?= $match['id'] ?>">
                                                    <button class="button button--black --width-full">Cancel</button>
                                                </form>
                                            <?php elseif ($team): ?>
                                                <div class="button button--primary --width-full" data-click='matchfinder-accept,modal' data-json='<?= $json ?>' data-modal='match-accept'>Accept</div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>

        <div class="column column--padding-left column--width-fixed column--width-full-1200px">
            <section class="section section--margin-top">
                <?php if ($data['counters.unreported']): ?>

                    <?php $section->start('important.html') ?>
                        <?php foreach ($data['unreported'] as $id): ?>
                            <div class="list-item list-item--bulletpoint">
                                <span>
                                    Your team <b class='--text-primary'><?= $app['auth']->getTeamById($id)['name'] ?></b>
                                    has an active match or has not reported the scores for an old match.
                                    Report that match before creating another. 
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php $section->end() ?>

                <?php elseif ($team): ?>

                    <?php if (($data['ladder.stopLoss'] > 0 || $data['ladder.entryFee'] > 0) && !$team['locked']): ?>
                        <?php $section->start('important.html') ?>
                            <span class="list-item list-item--bulletpoint">
                                Your team must lock your roster before creating a match.
                                Visit your team profile for more info.
                            </span>
                        <?php $section->end() ?>
                    <?php elseif ($data['ladder.stopLoss'] > 0 && ($team['losses'] + $data['counters.disputed']) >= $data['ladder.stopLoss']): ?>
                        <?php $section->start('important.html') ?>
                            <span class="list-item list-item--bulletpoint">
                                Your team has reached the maximum amount of losses allowed for this ladder.
                            </span>

                            <?php if ($data['counters.disputed']): ?>
                                <span class="list-item list-item--bulletpoint">
                                    Your team currently has <b><?= $data['counters.disputed'] ?></b> open disputes.
                                    Active dispute<?= $data['counters.disputed'] > 0 ? 's' : '' ?> count as a loss until resolved.
                                </span>
                            <?php endif; ?>
                        <?php $section->end() ?>
                    <?php else: ?>
                        <?php $app['modal']->add('@components/ladder/modals/match-create', compact('slugs', 'team')) ?>

                        <div class="button-group --flex-horizontal-space-between --margin-top-small --width-full">
                            <span class="button-group-item button button--large button--primary --width-full --width-half-1200px --margin-top-small" data-click='modal' data-modal="match-create">
                                Create A Match
                            </span>
                        </div>
                    <?php endif; ?>

                <?php endif; ?>

                <?php if ($section->has('important.html')): ?>
                    <?= $include('@components/list/important', [
                        'html' => $section('important.html')
                    ]) ?>
                <?php endif; ?>
            </section>
        </div>
    </div>
</div>
