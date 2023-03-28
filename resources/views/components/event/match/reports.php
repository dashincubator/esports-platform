<div class="button button--border-small button--tab --active --background-grey --border-dashed --border-default --flex-horizontal-space-between --margin-top-small --width-full" data-click='accordion' data-accordion='match-reports'>
    <b>Match Reports ( <span class="--text-black --text-bolder"><?= $data['count'] ?></span> )</b>

    <div class="accordion-arrow icon">
        <?= $app['svg']('arrow-head') ?>
    </div>
</div>

<div class="accordion card --active --background-grey --border --border-dashed --border-small" id='accordion-match-reports' style='max-height: 999px'>
    <div class="card-section text-lists">
        <div class="list list--margin-top-nested">
            <?= $data['!html'] ?>
        </div>
    </div>
</div>
