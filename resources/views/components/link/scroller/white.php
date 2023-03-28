<div class='link-scroller link-scroller--border-radius scroller --background-white --link-tab-height <?= $data['class'] ?>'>
    <div class='scroller-content <?= $data['scroller-content.class'] ?>' data-ref='scroller' data-wheel='scroller'>
        <div class="scroller-content-wrapper --container-padding <?= $data['scroller-content-wrapper.class'] ?>">

            <?php foreach ($data['links'] as $link): ?>
                <?php
                    if (!$link['svg'] && !$link['text']) {
                        continue;
                    }

                    $class = $app['html']->classes([
                        'link', 'link--border-active', 'link--border-bottom', 'link--tab', 'link--text',
                        '--border-color-not-active', '--border-grey',
                        '--icon-left',
                        '--text-bold',
                        $link['color']  ? "link--{$link['color']}" : 'link--primary',
                        $link['active'] ? '--active' : '',
                        $link['class']
                    ]);
                ?>

                <?= $include('links/default', compact('class', 'link')) ?>
            <?php endforeach; ?>

        </div>
    </div>
</div>
