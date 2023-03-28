<div class="table-row table-row--more-right-800px --background-grey-300 --margin-top-border <?= $data['class'] ?>">
    <b class="table-item table-item--width-small --flex-center">
        <?= $data['table.rank'] ?><?= is_numeric($data['table.rank']) ? $app['ordinal']($data['table.rank']) : '' ?>
    </b>

    <div class="table-item table-item--padding-left table-item--width-fill">
        <?= $data['!fill.html'] ?>
    </div>

    <span class="table-item table-item--width-small --hidden-800px  --flex-center"><?= $data['table.mp'] ?></span>
    <span class="table-item table-item--width-small --hidden-800px  --flex-center --text-green"><?= $data['table.wins'] ?></span>
    <span class="table-item table-item--width-small --hidden-800px  --flex-center --text-red"><?= $data['table.losses'] ?></span>
    <span class="table-item table-item--width-small --hidden-1000px --flex-center"><?= $data['table.wp'] ?>%</span>
    <span class="table-item table-item--width-small --hidden-800px  --flex-center"><?= $data['table.score'] ?></span>

    <div class="button button--clear button--large button--more button--white tooltip --absolute-vertical-right --border-color-override --border-grey --border-left --visible-800px" data-hover="tooltip">
        <div class="icon">
            <?= $app['svg']('menu/more') ?>
        </div>

        <div class="tooltip-content tooltip-content--ne tooltip-content--table --background-black-500 --cursor-default">
            <div class="scroller">
                <div class='scroller-content' data-ref='scroller' data-wheel='scroller'>
                    <div class="table table--tooltip">
                        <div class='table-header --background-black-500 --text-white'>
                            <div class='table-item table-item--width-small tooltip --text-center' data-hover='tooltip'>
                                MP
                                <span class='tooltip-content tooltip-content--message tooltip-content--c'>
                                    Matches Played
                                </span>
                            </div>
                            <div class='table-item table-item--width-small tooltip --text-center' data-hover='tooltip'>
                                W
                                <span class='tooltip-content tooltip-content--message tooltip-content--c'>
                                    Total Wins
                                </span>
                            </div>
                            <div class='table-item table-item--width-small tooltip --text-center' data-hover='tooltip'>
                                L
                                <span class='tooltip-content tooltip-content--message tooltip-content--c'>
                                    Total Losses
                                </span>
                            </div>
                            <div class='table-item table-item--width-small tooltip --text-center' data-hover='tooltip'>
                                W%
                                <span class='tooltip-content tooltip-content--message tooltip-content--c'>
                                    Average Win Percentage
                                </span>
                            </div>
                            <div class='table-item table-item--width-small tooltip --text-center' data-hover='tooltip'>
                                Score
                                <span class='tooltip-content tooltip-content--message tooltip-content--c'>
                                    Team Score
                                </span>
                            </div>
                        </div>

                        <div class="table-row --background-white --margin-top-border">
                            <span class="table-item table-item--width-small --flex-center"><?= $data['table.mp'] ?></span>
                            <span class="table-item table-item--width-small --flex-center --text-green"><?= $data['table.wins'] ?></span>
                            <span class="table-item table-item--width-small --flex-center --text-red"><?= $data['table.losses'] ?></span>
                            <span class="table-item table-item--width-small --flex-center"><?= $data['table.wp'] ?>%</span>
                            <span class="table-item table-item--width-small --flex-center"><?= $data['table.score'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
