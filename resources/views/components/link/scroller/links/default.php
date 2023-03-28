<?php
    $class = $data['class'];
    $link = $data['link'];

    $key = 'href';

    foreach (['accordion', 'frame', 'modal'] as $k) {
        $key = $link[$k] ? $k : $key;
    }

    if (!$link['svg'] && !$link['text']) {
        return;
    }
?>

<?php if ($key === 'href'): ?>
    <a class="<?= $class ?>" href='<?= $link['href'] ?>' <?= $link['target'] ? "target='{$link['target']}'" : '' ?>>
<?php else: ?>
    <div class="<?= $class ?>" data-click='<?= $key ?>' data-<?= $key ?>='<?= $link[$key] ?>'>
<?php endif; ?>

    <?php if ($link['svg']): ?>
        <div class="icon --absolute-vertical-left">
            <?= $app['svg']($link['svg']) ?>
        </div>
    <?php endif; ?>
    
    <?= $link['text'] ?>

<?php if ($key === 'href'): ?>
    </a>
<?php else: ?>
    </div>
<?php endif; ?>
