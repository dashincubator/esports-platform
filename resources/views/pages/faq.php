<?php $layout('@layouts/master/default') ?>

<div class="header-spacer"></div>

<div class="container">
    <section class="section section--margin-top-large">
        <div class="page-header">
            <h1 class="page-header-title">
                Frequently Asked Questions
            </h1>
            <span class="page-header-subtitle">
                Last Modified <b class='--text-black'><?= $app['time']->toFaqFormat($data['lastModified']) ?></b>
            </span>
        </div>
    </section>

    <section class='section section--margin-top-large --flex-center-800px'>
        <?php
            $links = [];

            foreach ($data['categories'] as $index => $category) {
                $links[] = [
                    'active' => ($index === 0),
                    'frame' => str_replace(' ', '-', strtolower($category['title'])),
                    'text' => $category['title']
                ];
            }
        ?>

        <?= $include('@components/link/scroller/border-grey', compact('links')) ?>
    </section>

    <?php $i = 0; ?>

    <div class="frames">
        <?php foreach ($data['categories'] as $index => $category): ?>
            <div class="frame <?= ($index === 0) ? '--active' : '' ?> --max-width-1200px" id="frame-<?= str_replace(' ', '-', strtolower($category['title'])) ?>">
                <section class="section section--margin-top">

                    <?= $include('@components/link/quicknav/border-grey', [
                        'button' => '--max-width-400px',
                        'prefix' => $i,
                        'sections' => $category['sections']
                    ]) ?>

                </section>

                <?php foreach ($category['sections'] as $index => $section): ?>
                    <section class="section section--margin-top<?= ($index > 0) ? '-small' : '' ?>">

                        <div class="page-header" id="scrollto-<?= $i . $index ?>">
                            <h3 class='page-header-title'><?= $section['title'] ?></h3>
                        </div>

                        <?php foreach ($section['content'] as $index => $content): ?>
                            <p><?= $content ?></p>
                        <?php endforeach; ?>

                    </section>
                <?php endforeach; ?>
            </div>

            <?php $i++; ?>
        <?php endforeach; ?>
    </div>
</div>
