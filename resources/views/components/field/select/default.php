<?php
    if (!$data['options']) {
        throw new \Exception("Select Field Missing Options");
    }

    $options = $data['options']->toArray();

    // Grouped Options
    if (is_array(array_values($options)[0] ?? '')) {
        $groups = $options;
        $selected = array_merge(...array_values($options))[$data['selected']] ?? array_values(array_values($options)[0])[0];
    }
    else {
        $groups = ['' => $options];
        $selected = array_values($options)[0] ?? '';
        $selected = $options[$data['selected']] ?? $selected;
    }
?>

<div
    class='field tooltip <?= $data['field.class'] ?>'
    <?= $app['html']->directives(array_merge(['click' => 'tooltip-toggle'], ($data['field.directives'] ? $data['field.directives']->toArray() : [])))['!html'] ?>
>
    <?= $data['!field.html'] ?>

    <?php if ($data['field-title']): ?>
        <?= $include('@components/field/title', array_merge($data['field-title']->toArray(), [
            'html' => $data['!field-title.html'],
            'required' => $data['field-tag.attributes.required']
        ])) ?>
    <?php endif; ?>

    <label
        class='field-text field-text--select'
        <?= $app['html']->directives($data['field-text.directives'] ? $data['field-text.directives']->toArray() : [])['!html'] ?>
    >
        <?= $data['!field-text.html'] ?>

        <select
            class='field-tag'
            <?= $app['html']->attributes($data['field-tag.attributes'] ? $data['field-tag.attributes']->toArray() : [])['!html'] ?>
            <?= $app['html']->directives($data['field-tag.directives'] ? $data['field-tag.directives']->toArray() : [])['!html'] ?>
        >
            <?php foreach ($groups as $title => $options): ?>
                <?php foreach ($options as $key => $value): ?>
                    <option value='<?= $key ?>' <?= $selected === $value ? 'selected' : '' ?>><?= $value ?></option>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </select>

        <div class='field-mask <?= $data['field-mask.class'] ?>' <?= $app['html']->directives($data['field-mask.directives'] ? $data['field-mask.directives']->toArray() : [])['!html'] ?>>
            <?= $selected ?>
        </div>

        <div class='tooltip-content tooltip-content--scrollbar tooltip-content--<?= $data['tooltip-content.direction'] ? $data['tooltip-content.direction'] : 's' ?> <?= $data['tooltip-content.class'] ?> --width-full'>
            <div class='tooltip-content-wrapper' data-ref='scrollbar' data-scroll='scrollbar' data-scrollbar='<?= $data['field-tag.attributes.name'] ?>'>
                <?php foreach ($groups as $title => $options): ?>
                    <?php if ($title !== ''): ?>
                        <div class="border border--small <?= $data['border.class'] ?>"></div>
                    <?php endif; ?>

                    <div class='link-menu link-menu--padding <?= $data['link-menu.class'] ?>'>
                        <?php if ($title !== ''): ?>
                            <span class='link-title <?= $data['link-title.class'] ?> --width-full'><?= $title ?></span>
                        <?php endif; ?>

                        <?php foreach ($options as $key => $value): ?>
                            <div class='link <?= $data['link.class'] ?> <?= $selected === $value ? '--active' : '' ?> --width-full' data-click='field-select' data-value='<?= $key ?>'>
                                <?= $value ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class='scrollbar <?= $data['scrollbar.class'] ?>' id='scrollbar-<?= $data['field-tag.attributes.name'] ?>'></div>
        </div>
    </label>

    <?php if ($data['field-description']): ?>
        <?= $include('@components/field/description', array_merge($data['field-description']->toArray(), [
            'html' => $data['!field-description.html']
        ])) ?>
    <?php endif; ?>
</div>
