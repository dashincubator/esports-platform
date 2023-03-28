<?php
    $layout('master/default');

    $game = $app['game']->get($data['ladder.game']);
    $platform = $app['platform']->get($game['platform']);
    $slugs = [
        'game' => $game['slug'],
        'ladder' => $data['ladder.slug'],
        'platform' => $platform['slug']
    ];

    $app['seo']->{$data['ladder.type']}($game, $data['ladder'], $platform);
?>

<?php $section->prepend('buttons') ?>
    <?php if ($app['auth']->isGuest()): ?>
        <?= $include('@components/event/header/buttons/sign-in') ?>
    <?php else: ?>
        <?php if ($app['auth']->onLadderTeam($data['ladder.id'])): ?>
            <?= $include('@components/event/header/buttons/view-team', [
                'href' => $app['route']->uri('ladder.team', $slugs + ['team' => $app['auth']->getTeamByLadder($data['ladder.id'])['slug']])
            ]) ?>
        <?php elseif ($data['ladder.isRegistrationOpen']): ?>
            <?= $include('@components/event/header/buttons/create-team', [
                'action' => $app['route']->uri('ladder.join.command', $slugs),
                'button' => [
                    'text' => 'Join ' . ucfirst($data['ladder.type'])
                ],
                'title' => "Create {$data['ladder.name']} Team"
            ]) ?>
        <?php endif; ?>
    <?php endif; ?>
<?php $section->end() ?>

<?= $include('@components/game/header', [
    'buttons' => $section('buttons'),
    'game' => $game,
    'links' => [
        [
            'active' => ($app['route']->is('ladder') || $app['route']->is('ladder.leaderboard')),
            'frame' => 'leaderboard',
            'svg' => 'league',
            'text' => 'Leaderboard'
        ],
        ($data['ladder.isMatchFinderRequired'] ? [
            'active' => $app['route']->is('ladder.matchfinder'),
            'href' => $app['route']->uri('ladder.matchfinder', $slugs),
            'svg' => 'match',
            'text' => 'Match Finder'
        ] : []),
        [
            'active' => $app['route']->is('ladder.prizes'),
            'frame' => 'prizes',
            'svg' => 'trophy',
            'text' => 'Prizes'
        ],
        [
            'active' => $app['route']->is('ladder.rules'),
            'frame' => 'rules',
            'svg' => 'rules',
            'text' => 'Rules'
        ],
        [
            'active' => $app['route']->is('ladder.faq'),
            'frame' => 'faq',
            'svg' => 'question',
            'text' => 'Frequently Asked Questions'
        ]
    ],
    'stats' => [
        'items' => $app['ladder']->toTextListArrayShort($data['ladder']),
        'membership' => $data['ladder.isMembershipRequired']
    ],
    'subtitle' => "{$platform['name']} {$game['name']} " . ucwords($data['ladder.type']),
    'title' => $data['ladder.name']
]) ?>

<?= $section('content') ?>
