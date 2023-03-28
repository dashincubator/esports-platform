<div class="field field--border <?= $data['field.class'] ?>" data-focusinout="toggle">
    <span class="field-title"><?= $data['field-title.text'] ?></span>

    <label class="field-text field-text--upload">
        <input accept="image/png, image/jpeg, image/jpg" class="field-tag" data-change="field-upload" name='<?= $data['field-tag.attributes.name'] ?>' type="file">
        <div class='field-mask'>
            <div class='--text-truncate'>Drop file here or <b>click to upload</b></div>
        </div>
    </label>
</div>
