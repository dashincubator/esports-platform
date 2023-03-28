<div class="frame" id="frame-header-ladders">
    <div class="header-user-menu-header">
        <?= $include('components/close') ?>

        <b class="text --text-large --text-white">
            Manage Ladders
        </b>
    </div>

    <?= $include('@components/header/frames/components/links', ['links' => [
        ($app['auth']->can('manageLadders') ? [
            'route' => [
                'name' => 'admincp.ladder.create'
            ],
            'svg' => 'plus-circle',
            'text' => 'Add Ladder'
        ] : []),
        ($app['auth']->can('manageLadderGametypes') ? [
            'route' => [
                'name' => 'admincp.ladder.gametype.manage'
            ],
            'svg' => 'match',
            'text' => 'Manage Ladder Gametypes'
        ] : [])
    ]]) ?>

    <?php if ($app['auth']->can('manageLadders')): ?>
        <?php foreach(['ladder', 'league'] as $key): ?>
            <div class="border border--grey border--small"></div>

            <div class='link-menu link-menu--padding --background-grey-300'>
                <div class="link-title link-title--button-menu">
                    Update <?= ucfirst($key) ?>
                </div>

                <?php $events = $app['ladder']->{'filterBy' . ucfirst($key) . 's'}() ?>

                <?php if (!count($events)): ?>
                    <?= $include('components/link/empty', [
                        'text' => "0 {$key}s found"
                    ]) ?>
                <?php endif; ?>

                <?php foreach($events as $event): ?>
                    <?php
                        if (!$app['auth']->managesGame($event['game'])) {
                            continue;
                        }

                        $game = $app['game']->get($event['game']);
                        $platform = $app['platform']->get($game['platform']);
                    ?>

                    <a class='link link--button-menu link--button-white link--text --width-full' href='<?= $app['route']->uri('admincp.ladder.edit', ['id' => $event['id']]) ?>'>
                        <div class="text-list --game-icons-small-left">
                            <?= $include('@components/game/icons/default', [
                                'class' => '--absolute-vertical-left',
                                'game' => $game,
                                'small' => true
                            ]) ?>

                            <b class="text">
                                <?= $event['name'] ?>
                            </b>
                            <div class="text --text-small">
                                <span class="--text-truncate">
                                    <?= $platform['name'] ?> <?= $game['name'] ?>
                                </span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
