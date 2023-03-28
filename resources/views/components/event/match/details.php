<div class="button button--border-small button--tab --background-grey --border-dashed --border-default --flex-horizontal-space-between --margin-top-small --width-full" data-click='accordion' data-accordion='match-settings'>
    <b>Match Details</b>

    <div class="accordion-arrow icon">
        <?= $app['svg']('arrow-head') ?>
    </div>
</div>

<div class="accordion card --background-grey --border --border-dashed --border-small" id='accordion-match-settings'>
    <div class="card-section text-lists">
        <?php
            $items = $data['items']->toArray();

            foreach ($items as $index => &$item) {
                if ($item['title'] !== 'Modifiers') {
                    continue;
                }

                $items[$index]['list'] = true;
            }
        ?>

        <?= $include('@components/text/list', [
            'items' => $items,
            'order' => ['title', 'text']
        ]) ?>
    </div>
</div>
