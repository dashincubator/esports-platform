<div class="modal modal--large" data-click="modal" data-modal="games" data-modifier='grey' id='modal-games'>
    <div class="modal-content modal-content--frames">

        <div class="game-modal-events">
            <section class='section'>
                <div class="page-header">
                    <h2 class="page-header-title">
                        Upcoming Events
                    </h2>
                    <span class="page-header-subtitle --text-crop-bottom">
                        Filter events by selecting a game title
                    </span>
                </div>
            </section>

            <?php
                $i = 0;
                $events = [];
                $games = $app['game']->getAllBySlugs();

                foreach ($games as $slug => $group) {
                    foreach ($group as $game) {

                        foreach (['ladder'] as $key) {
                            foreach ($app[$key]->filterByGame($game['id']) as $event) {
                                $events[$slug][] = $event;
                            }
                        }

                    }
                }
            ?>

            <?php if (count($events)): ?>
                <section class="section section--margin-top-small">
                    <div class="scroller">
                        <div class="scroller-content">
                            <div class="scroller-content-wrapper">
                                <?php foreach($events as $slug => $event): ?>
                                    <div class="index-game <?= $i === 0 ? '--active' : '' ?>" data-click='frame' data-frame='menu-<?= $slug ?>'>
                                        <div class="index-game-banner" style='background-image: url(<?= "/images/index/{$slug}.jpg" ?>)'></div>
                                    </div>

                                    <?php $i++; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="frames frames--overflow">
                    <?php $i = 0; ?>

                    <?php foreach ($events as $slug => $group): ?>
                        <div class="frame <?= $i === 0 ? '--active' : '' ?>" id='frame-menu-<?= $slug ?>'>
                            <?php
                                $filter = [
                                    'all' => 'All Platforms'
                                ];
                            ?>

                            <?php $section->start('rows') ?>
                                <?php foreach ($group as $event): ?>
                                    <?php
                                        $game = $app['game']->get($event['game']);
                                        $platform = $app['platform']->get($game['platform']);

                                        $filter["{$slug}{$platform['id']}"] = $platform['name'] . ' Only';
                                        $slugs = [
                                            'platform' => $platform['slug'],
                                            'game' => $game['slug']
                                        ];

                                        if ($event['isLadder'] || $event['isLeague']) {
                                            $row = $app['ladder']->toHomepageRowFormat($event);
                                            $tooltip = $app['ladder']->toTextListArrayShort($event);
                                            $url = $app['route']->uri('ladder', $slugs + ['ladder' => $event['slug']]);
                                        }
                                    ?>

                                    <div class="filter table-row table-row--more-right-1000px --active --background-grey-300 --margin-top-border" data-filter='<?= "{$slug}{$platform['id']}" ?>' data-ref='filter'>
                                        <div class="table-item table-item--padding-right table-item--width-fill --flex-vertical --game-icons-small-left --game-icons-left-hidden-600px">
                                            <?= $include('@components/game/icons/default', [
                                                'class' => '--absolute-vertical-left --hidden-600px',
                                                'game' => $game,
                                                'small' => true
                                            ]) ?>

                                            <div class="text-list">
                                                <a class="link link--color link--primary text --inline --text-bold --text-large --text-truncate" href='<?= $url ?>'>
                                                    <?= $event['name'] ?>
                                                </a>

                                                <div class="text">
                                                    <span class='--text-small --text-truncate'>
                                                        <b class='--text-black'><?= $event['totalRegisteredTeams'] ?></b> Registered Teams
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="table-item table-item--width-smedium --flex-vertical --hidden-1000px">
                                            <?php if ($event['entryFee'] > 0): ?>
                                                <div class="text-list">
                                                    <b class="text">$<?= number_format($event['entryFee']) ?></b>
                                                    <span class="text --text-small">Per Player</span>
                                                </div>
                                            <?php else: ?>
                                                <b class="text">Free Entry</b>
                                            <?php endif; ?>
                                        </div>

                                        <div class="table-item table-item--width-large --flex-vertical --hidden-1000px">
                                            <?php $start = explode(',', $row['start']) ?>

                                            <div class="text-list">
                                                <span class="text --text-small"><?= $start[0] ?></span>
                                                <b class="text"><?= $start[1] ?></b>
                                            </div>
                                        </div>

                                        <div class="table-item table-item--width-large --flex-vertical --hidden-1000px">
                                            <?php $end = explode(',', $row['ends']) ?>

                                            <div class="text-list">
                                                <span class="text --text-small"><?= $end[0] ?></span>
                                                <b class="text"><?= $end[1] ?></b>
                                            </div>
                                        </div>

                                        <div class="table-item table-item--width-small --flex-vertical">
                                            <a class="button button--primary --width-full" href='<?= $url ?>'>
                                                View
                                            </a>
                                        </div>

                                        <div class="button button--clear button--large button--more button--white tooltip --absolute-vertical-right --border-color-override --border-grey --border-left --visible-1000px" data-hover="tooltip">
                                            <div class="icon">
                                                <?= $app['svg']('menu/more') ?>
                                            </div>

                                            <div class="tooltip-content tooltip-content--ne --background-white" data-stophover data-stopclick>
                                                <?= $include('@components/tooltip/section', [
                                                    'items' => $tooltip
                                                ]) ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php $section->end() ?>

                            <section class="section section--margin-top-small">
                                <div class="page-header">
                                    <h4 class='page-header-title --text-crop-both'>
                                        Filter By
                                    </h4>
                                </div>

                                <div class="button-group --width-full">
                                    <?= $include('@components/field/select/border', [
                                        'field' => [
                                            'class' => 'button-group-item field--black --background-grey --margin-top-small --max-width-200px --width-full'
                                        ],
                                        'field-tag' => [
                                            'directives' => [
                                                'change' => 'filter'
                                            ]
                                        ],
                                        'options' => $filter,
                                        'tooltip-content' => [
                                            'direction' => 'sw'
                                        ]
                                    ]) ?>
                                </div>

                                <div class="table table--margin-top">
                                    <div class="table-header table-header--more-right-1000px --background-black-500 --text-white">
                                        <span class="table-item table-item--padding-right table-item--width-fill --text-truncate">
                                            <?= $game['name'] ?> Events
                                        </span>

                                        <span class="table-item table-item--width-smedium --hidden-1000px">
                                            Entry Fee
                                        </span>

                                        <span class="table-item table-item--width-large --hidden-1000px">
                                            Event Starts
                                        </span>

                                        <span class="table-item table-item--width-large --hidden-1000px">
                                            Event Ends
                                        </span>

                                        <span class="table-item table-item--width-small --hidden-1000px"></span>
                                    </div>

                                    <?= $section('rows') ?>
                                </div>
                            </section>
                        </div>

                        <?php $i++; ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <section class="section section--margin-top-small">
                    <div class="card --background-grey --border-dashed --border-small --width-full">
                        <div class="card-section --text-center">

                            No events found

                        </div>
                    </div>
                </section>
            <?php endif; ?>

        </div>
    </div>
</div>
