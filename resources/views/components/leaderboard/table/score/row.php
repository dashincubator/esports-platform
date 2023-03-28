<div class="table-row --background-grey-300 --margin-top-border <?= $data['class'] ?>">
    <b class="table-item table-item--width-small --flex-center">
        <?= $data['table.rank'] ?><?= is_numeric($data['table.rank']) ? $app['ordinal']($data['table.rank']) : '' ?>
    </b>

    <div class="table-item table-item--padding-left table-item--width-fill">
        <?= $data['!fill.html'] ?>
    </div>

    <?php if ($data['userprofile']): ?>
        <span class="table-item table-item--width-small --flex-center">-</span>
        <span class="table-item table-item--width-small --flex-center">-</span>
        <span class="table-item table-item--width-small --flex-center">-</span>
        <span class="table-item table-item--width-small --flex-center">-</span>
    <?php endif; ?>

    <span class="table-item table-item--width-small --flex-center"><?= $data['table.score'] ?></span>
</div>
