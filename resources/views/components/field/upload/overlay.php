<label class="upload-overlay tooltip" data-hover='tooltip'>
    <span class="tooltip-content tooltip-content--<?= $data['tooltip-content.direction'] ? $data['tooltip-content.directio'] : 'c' ?> tooltip-content--message --cursor-pointer">
        <?= $data['tooltip-content.text'] ?>
    </span>

    <div class="upload-overlay-button button button--circle button--grey button--large --cursor-pointer">
        <div class="icon icon--large">
            <?= $app['svg']('camera') ?>
        </div>
    </div>

    <input
        accept="image/png, image/jpeg, image/jpg"
        class="upload-overlay-input"
        data-change="upload-<?= $data['onchange'] ? 'onchange' : 'update' ?>"
        data-upload-update='<?= $data['field-tag.attributes.name'] ?>'
        name='<?= $data['field-tag.attributes.name'] ?>'
        type="file"
    >
</label>
