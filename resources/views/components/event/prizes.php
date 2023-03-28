<div class="container">
    <section class="section section--margin-top">
        <?php if (count($data['prizes'])): ?>
            <div class="page-header">
                <h2 class="page-header-title --text-crop-top">Prize Breakdown</h2>
                <span class="page-header-subtitle">
                    <?= $data['subtitle'] ?>
                </span>
            </div>

            <div class="table table--margin-top">
                <div class="table-header --background-black-500 --text-white">
                    <span class="table-item table-item--width">Place</span>
                    <span class="table-item">Prize</span>
                </div>

                <?php $i = 0; ?>

                <?php foreach ($data['prizes'] as $title => $prizes): ?>
                    <div class="table-row table-row--border<?= $i === 0 ? '-primary' : '-black' ?> --background-grey-300 --margin-top-border">
                        <b class="table-item table-item--width">
                            <?= $title ?>
                        </b>

                        <div class="table-item table-item--width-fill">
                            <?php foreach ($prizes as $prize): ?>
                                <p><?= $prize ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php $i++; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <b class="button button--tab button--border-small button--static --background-grey --border-dashed --border-default --width-full">
                No Prizes Have Been Added To This Event
            </b>
        <?php endif; ?>
    </section>

    <section class="section section--margin-top">
        <?php
            $links = [];

            foreach ($data['info'] as $index => $category) {
                $links[] = [
                    'active' => ($index === 0),
                    'color' => 'black',
                    'frame' => str_replace(' ', '-', strtolower($category['title'])),
                    'text' => $category['title']
                ];
            }
        ?>

        <?= $include('@components/link/scroller/border-grey', compact('links')) ?>
    </section>

    <section class="section">
        <?php $i = 0; ?>

        <div class="frames">
            <?php foreach ($data['info'] as $index => $category): ?>
                <div class="frame <?= ($index === 0) ? '--active' : '' ?> --max-width-1200px" id="frame-<?= str_replace(' ', '-', strtolower($category['title'])) ?>">
                    <section class="section section--margin-top --flex-center-800px">

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
    </section>
</div>
