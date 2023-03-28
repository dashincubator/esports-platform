<label
    class='button button--<?= $data['field.size'] ? $data['field.size'] : 'large' ?> button--border-dashed button--border-small field --border-default <?= $data['field.class'] ?>'
    <?= $app['html']->directives(array_merge(['change' => 'field-checkbox'], ($data['field.directives'] ? $data['field.directives']->toArray() : [])))['!html'] ?>
>
    <?= $data['!field.html'] ?>

    <div
        class='field-check field-check--right field-check--switch'
        <?= $app['html']->directives($data['field-check.directives'] ? $data['field-check.directives']->toArray() : [])['!html'] ?>
    >
        <?= $data['!field-check.html'] ?>

        <?php if ($data['field-title']): ?>
            <?= $include('@components/field/title', array_merge($data['field-title']->toArray(), [
                'class' => "{$data['field-title.class']} --text-bold",
                'html' => $data['!field-title.html'],
                'required' => $data['field-tag.attributes.required']
            ])) ?>
        <?php endif; ?>

        <?php if ($data['field-description']): ?>
            <?= $include('@components/field/description', array_merge($data['field-description']->toArray(), [
                'html' => $data['!field-description.html']
            ])) ?>
        <?php endif; ?>

        <div class='field-mask' <?= $app['html']->directives($data['field-mask.directives'] ? $data['field-mask.directives']->toArray() : [])['!html'] ?>>
            <input name='<?= $data['field-tag.attributes.name'] ?>' type='hidden' value='0'>

            <?php
                $attributes = $data['field-tag.attributes'] ? $data['field-tag.attributes']->toArray() : [];

                if (!($attributes['checked'] ?? false)) {
                    unset($attributes['checked']);
                }
            ?>

            <input
                class='field-tag'
                data-ref='trigger:change'
                type='checkbox'
                value='1'
                <?= $app['html']->attributes($attributes)['!html'] ?>
                <?= $app['html']->directives($data['field-tag.directives'] ? $data['field-tag.directives']->toArray() : [])['!html'] ?>
            >
        </div>
    </div>
</label>
