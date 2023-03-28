<?php
    $direction = $data['border.direction'] ? $data['border.direction'] : 'bottom';
    $offset = $direction !== 'bottom' ? 'bottom' : 'top';

    if ($data['offset'] === false) {
        $offset = 'none';
    }
?>

<div class="link-scroller link-scroller--border-<?= $direction ?> link-scroller--offset-<?= $offset ?> scroller --border-grey-500 --link-tab-height <?= $data['class'] ?>">
    <div class="scroller-content <?= $data['scroller-content.class'] ?>" data-ref='scroller' data-wheel='scroller'>
        <div class="scroller-content-wrapper <?= $data['scroller-content-wrapper.class'] ?>">

            <?php foreach ($data['links'] as $link): ?>
                <?php
                    $class = $app['html']->classes([
                        'link', 'link--border-active', "link--border-{$direction}", 'link--icon-active', 'link--tab', 'link--text',
                        '--border-color-not-active', '--border-grey-500',
                        '--text-bold',
                        ($link['color']  ? "link--{$link['color']}" : 'link--primary'),
                        ($link['active'] ? '--active' : ''),
                        ($link['svg'] ? '--icon-left' : ''),
                        $link['class']
                    ]);
                ?>

                <?= $include('links/default', compact('class', 'link')) ?>
            <?php endforeach; ?>

        </div>
    </div>
</div>
