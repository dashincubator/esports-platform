<div class="frame" id="frame-header-platforms">
    <div class="header-user-menu-header">
        <?= $include('components/close') ?>

        <b class="text --text-large --text-white">
            Manage Platforms
        </b>
    </div>

    <?= $include('@components/header/frames/components/links', ['links' => [
        [
            'route' => [
                'name' => 'admincp.platform.create'
            ],
            'svg' => 'plus-circle',
            'text' => 'Add Platform'
        ]
    ]]) ?>

    <div class="border border--grey border--small"></div>

    <div class='link-menu link-menu--padding --background-grey-300'>
        <div class="link-title link-title--button-menu">
            Update Platform
        </div>

        <?php
            $games = $app['game']->getAllByPlatforms();
            $platforms = $app['platform']->getAll();
        ?>

        <?php if (!count($platforms)): ?>
            <?= $include('components/link/empty', [
                'text' => '0 platforms found'
            ]) ?> 
        <?php endif; ?>

        <?php foreach($platforms as $platform): ?>
            <?php $count = count($games[$platform['id']] ?? []) ?>

            <a class='link link--button-menu link--button-white link--text --width-full' href='<?= $app['route']->uri('admincp.platform.edit', ['id' => $platform['id']]) ?>'>
                <div class="text-list --button-small-left">
                    <div class="button button--circle button--small button--static button--<?= $platform['view'] ?> --absolute-vertical-left">
                        <div class="icon">
                            <?= $app['svg']('platform/' . $platform['view']) ?>
                        </div>
                    </div>

                    <b class="text">
                        <?= $platform['name'] ?>
                    </b>
                    <div class="text --text-small">
                        <span class="--text-truncate">
                            <?= $count ?> Active Game<?= ($count > 1 || $count === 0) ? 's' : '' ?>
                        </span>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

</div>
