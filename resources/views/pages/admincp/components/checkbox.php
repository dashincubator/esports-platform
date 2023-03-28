<label class="button button--large button--basic button--transparent field field--primary --background-grey --width-full" data-change='field-checkbox'>
    <div class="field-check field-check--right field-check--switch">
        <div class="text-list">
            <b class="text">
                <?= $data['title'] ?>
            </b>
            <div class="text --text-small">
                <?= $data['description'] ?>
            </div>
        </div>

        <span class="field-mask">
            <input class="field-tag" name='permissions[]' type="checkbox" data-ref='trigger:change' value="<?= $data['value'] ?>" <?= $data['checked'] ? 'checked' : '' ?>>
        </span>
    </div>
</label>

<div class="border border--dashed border--small"></div>
