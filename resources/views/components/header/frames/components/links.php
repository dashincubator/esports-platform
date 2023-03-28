<div class='link-menu link-menu--padding --background-grey-300'>
    <?php if ($data['link-title']): ?>
        <div class="link-title link-title--button-menu">
            <?= $data['link-title'] ?>
        </div>
    <?php endif; ?>

    <?php foreach ($data['links'] as $link): ?>
        <?php if ($link['frame']): ?>
            <div class='link link--button-menu link--button-white link--text --width-full' data-click='frame' data-frame='<?= $link['frame'] ?>' data-frame-ignore>
                <div class='--flex-horizontal-space-between <?= $link['svg'] ? '--icon-left' : '' ?> --icon-right --text-truncate --width-full'>
                    <?php if ($link['svg']): ?>
                        <div class='icon --absolute-vertical-left'>
                            <?= $app['svg']($link['svg']) ?>
                        </div>
                    <?php endif; ?>

                    <?= $link['text'] ?>

                    <div class='icon icon--small --absolute-vertical-right'>
                        <?= $app['svg']('arrow-head') ?>
                    </div>
                </div>
            </div>
        <?php elseif ($link['route']): ?>
            <a class='link link--button-menu link--button-white link--text --width-full' href='<?= $app['route']->uri($link['route.name'], $link['route.variables'] ? $link['route.variables']->toArray() : []) ?>'>
                <div class='--flex-horizontal-space-between <?= $link['svg'] ? '--icon-left' : '' ?> --text-truncate --width-full'>
                    <?php if ($link['svg']): ?>
                        <div class='icon --absolute-vertical-left'>
                            <?= $app['svg']($link['svg']) ?>
                        </div>
                    <?php endif; ?>

                    <?= $link['text'] ?>
                </div>
            </a>
        <?php endif; ?>

    <?php endforeach; ?>
</div>
