<div class="button button--border-small button--tab --background-grey --border-dashed --border-default --flex-horizontal-space-between --width-full" data-click='accordion' data-accordion='match-maps'>
    <b>Maps</b>

    <div class="accordion-arrow icon">
        <?= $app['svg']('arrow-head') ?>
    </div>
</div>

<div class="accordion --background-grey --border --border-dashed --border-small" id='accordion-match-maps'>
    <div class='table'>
        <?php foreach ($data['maps'] as $index => $mapset): ?>
            <?php
                $host = $data["hosts.{$index}"];

                if (!$host) {
                    break;
                }
            ?>

            <div class="table-row">
                <div class="table-item table-item--width-fill">
                    <div class="text --text-black --text-bold --text-icon-crop-top">
                        Map <?= ($index + 1) ?>
                    </div>

                    <div class="list list--margin-top-nested">
                        <div class="list-item list-item--bulletpoint list-item--margin-top-nested">
                            <b><?= $mapset[0] ?></b>

                            <?php if (isset($mapset[1])): ?>
                                <?= $mapset[1] ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="text --margin-top --text-black --text-bold">
                        Host
                    </div>

                    <div class="list list--margin-top-nested">
                        <span class="list-item list-item--bulletpoint list-item--margin-top-nested"><?= $host ?></span>
                    </div>
                </div>
            </div>

            <div class="border border--dashed border--small"></div>
        <?php endforeach; ?>
    </div>
</div>
