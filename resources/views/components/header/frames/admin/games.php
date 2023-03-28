<div class="frame" id="frame-header-games">
    <div class="header-user-menu-header">
        <?= $include('components/close') ?>

        <b class="text --text-large --text-white">
            Manage Games
        </b>
    </div>

    <?= $include('@components/header/frames/components/links', ['links' => [
        [
            'route' => [
                'name' => 'admincp.game.create'
            ],
            'svg' => 'plus-circle',
            'text' => 'Add Game'
        ]
    ]]) ?>

    <div class="border border--grey border--small"></div>

    <div class='link-menu link-menu--padding --background-grey-300'>
        <div class="link-title link-title--button-menu">
            Update Game
        </div>

        <?php $games = $app['game']->getAll() ?>

        <?php if (!count($games)): ?>
            <?= $include('components/link/empty', [
                'text' => '0 games found'
            ]) ?> 
        <?php endif; ?>

        <?php foreach($games as $game): ?>
            <a class='link link--button-menu link--button-white link--text --width-full' href='<?= $app['route']->uri('admincp.game.edit', ['id' => $game['id']]) ?>'>
                <div class="text-list --game-icons-small-left">
                    <?= $include('@components/game/icons/default', [
                        'class' => '--absolute-vertical-left',
                        'game' => $game,
                        'small' => true
                    ]) ?>

                    <b class="text">
                        <?= $game['name'] ?>
                    </b>
                    <div class="text --text-small">
                        <span class="--text-truncate">
                            <?= $app['platform']->get($game['platform'])['name'] ?>
                        </span>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

</div>
