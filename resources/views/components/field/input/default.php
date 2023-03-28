<div
    class='field <?= $data['field.class'] ?>'
    <?= $app['html']->directives(array_merge(['focusinout' => 'toggle'], ($data['field.directives'] ? $data['field.directives']->toArray() : [])))['!html'] ?>
>
    <?= $data['!field.html'] ?>

    <?php if ($data['field-title']): ?>
        <?= $include('@components/field/title', array_merge($data['field-title']->toArray(), [
            'html' => $data['!field-title.html'],
            'required' => $data['field-tag.attributes.required']
        ])) ?>
    <?php endif; ?>

    <label
        class='field-text field-text--input'
        <?= $app['html']->directives($data['field-text.directives'] ? $data['field-text.directives']->toArray() : [])['!html'] ?>
    >
        <?= $data['!field-text.html'] ?>

        <input
            class='field-mask field-tag <?= $data['field-mask.class'] ?> <?= $data['field-tag.class'] ?>'
            <?= $app['html']->attributes(array_merge(['type' => 'text'], ($data['field-tag.attributes'] ? $data['field-tag.attributes']->toArray() : [])))['!html'] ?>
            <?= $app['html']->directives($data['field-mask.directives'] ? $data['field-mask.directives']->toArray() : [])['!html'] ?>
            <?= $app['html']->directives($data['field-tag.directives'] ? $data['field-tag.directives']->toArray() : [])['!html'] ?>
        >
    </label>

    <?php if ($data['field-description']): ?>
        <?= $include('@components/field/description', array_merge($data['field-description']->toArray(), [
            'html' => $data['!field-description.html']
        ])) ?>
    <?php endif; ?>
</div>
