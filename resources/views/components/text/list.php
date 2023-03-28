<?php
    $class = $data['class'];
    $large = $data['large'];
    $list  = $data['list'];
    $order = $data['order'];
    $white = $data['white'];

    if (!$order) {
        $order = ['text', 'title'];
    }
?>

<?php foreach ($data['items'] as $item): ?>
    <?php
        $svg = $item['svg'];
        $text = $item['text'];
        $title = $item['title'];
        $urls = $item['urls'];
    ?>

    <div class="text-list <?= $svg ? '--icon-left' : '' ?> <?= $class ?>">
        <?php if ($svg): ?>
            <div class="icon --absolute-vertical-left <?= $white ? '--text-white' : '' ?>">
                <?= $app['svg']($svg) ?>
            </div>
        <?php endif; ?>

        <?php foreach ($order as $key): ?>
            <?php if ($key === 'text' && $text !== ''): ?>
                <?php $text = is_scalar($text) ? [$text] : $text; ?>

                <div class="text-<?= ($list || $item['list'] || $urls) ? 'list' : 'group' ?>">
                    <?php if ($urls): ?>

                        <?php foreach ($text as $t): ?>
                            <?php
                                if ($t[0] !== '/' && strpos('http', $t) === false) {
                                    $t = "http://{$t}";
                                }
                            ?>

                            <b class="text <?= $large ? '--text-large' : '' ?> <?= $white ? '--text-white' : '' ?>">
                                <a href="<?= $t ?>" class="link link--primary --inline --text-truncate" target="_blank"><?= $t ?></a>
                            </b>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <?php foreach ($text as $t): ?>
                            <b class="text <?= $large ? '--text-large' : '' ?> <?= $white ? '--text-white' : '' ?>"><?= $t ?></b>
                        <?php endforeach; ?>

                    <?php endif; ?>
                </div>
            <?php elseif ($key === 'title' && $title !== ''): ?>
                <span class="text --text-small <?= $white ? '--text-grey' : '' ?>">
                    <?= $title ?>
                </span>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>
