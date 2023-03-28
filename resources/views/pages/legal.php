<?php $layout('@layouts/master/default') ?>

<div class="header-spacer"></div>

<div class="container container--width-1200px">
    <section class="section section--margin-top-large">
        <div class="page-header page-header--title --flex-horizontal --text-center">
            <h1 class="page-header-title"><?= $data['title'] ?></h1>
            <span class="page-header-subtitle">Last Modified <b class='--text-black'><?= $app['time']->toLegalFormat($data['lastModified']) ?></b></span>

            <?php $sections = $data['sections']->toArray(); ?>

            <?php if (array_column($data['sections']->toArray(), 'title')): ?>
                <div class="--margin-top-large --max-width-400px --width-full">
                    <?= $include('@components/link/quicknav/border-grey', ['sections' => $sections]) ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="section section--margin-top-large">
        <?php $i = 0; ?>

        <?php foreach ($data['sections'] as $section): ?>
            <section class="section <?= $i > 0 ? 'section--margin-top-small' : '' ?>" id="scrollto-<?= $i ?>">
                <?php if ($section['title']): ?>
                    <div class="page-header">
                        <h3 class='page-header-title <?= $i === 0 ? '--text-crop-top' : '' ?>'><?= $section['title'] ?></h3>
                    </div>
                <?php endif; ?>

                <?php foreach (($section['content'] ?? []) as $content): ?>
                    <p><?= $content ?></p>
                <?php endforeach; ?>

                <?php $i++ ?>
            </section>
        <?php endforeach; ?>
    </section>
</div>
