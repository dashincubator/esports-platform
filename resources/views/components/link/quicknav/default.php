<div class="button button--tab tooltip --flex-horizontal-space-between --width-full <?= $data['button'] ?>" data-click='tooltip'>
    <b>Quick Navigation</b>

    <div class="tooltip-arrow icon">
        <?= $app['svg']('arrow-head') ?>
    </div>

    <div class="tooltip-content tooltip-content--menu tooltip-content--s tooltip-content--scrollbar --width-full <?= $data['tooltip'] ?>">
        <div class='tooltip-content-wrapper' data-ref='scrollbar' data-scroll='scrollbar' data-scrollbar='quicknav-<?= $data['prefix'] ?>'>
            <?php
                $i = 0;
                $titles = $data['sections.0.sections'] !== '';
            ?>

            <?php if (!$titles): ?>
                <div class="border border--grey-500 border--small"></div>

                <div class='link-menu link-menu--padding --text-left'>
            <?php endif; ?>

            <?php foreach ($data['sections'] as $section): ?>
                <?php
                    if (!$section['title']) {
                        $i++;
                        continue;
                    }
                ?>

                <?php if ($section['sections'] == ''): ?>
                    <span class="link link--button-menu --line-height-400 --width-full <?= $data['link'] ?>" data-click="tooltip-detoggle, scrollto" data-scrollto="<?= $data['prefix'] . $i ?>"><?= $section['title'] ?></span>

                    <?php $i++; ?>
                <?php else: ?>
                    <div class="border border--grey-500 border--small"></div>

                    <div class='link-menu link-menu--padding --text-left'>
                        <span class='link-menu-title link-title link-title--button-menu'><?= $section['title'] ?></span>

                        <?php foreach ($section['sections'] as $section): ?>
                            <span class="link link--button-menu --line-height-400 --width-full <?= $data['link'] ?>" data-click="tooltip-detoggle,scrollto" data-scrollto="<?= $data['prefix'] . $i ?>">
                                <?= $section['title'] ?>
                            </span>

                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if (!$titles): ?>
                </div>
            <?php endif; ?>
        </div>

        <div class='scrollbar --background-border-default' id='scrollbar-quicknav-<?= $data['prefix'] ?>'></div>
    </div>
</div>
