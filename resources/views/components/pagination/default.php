<?php
    $limit = $data['limit'] ? $data['limit'] : 25;
    $page = $data['page'] ? $data['page'] : 1;
    $pages = $data['pages'] ? $data['pages'] : 1;

    $text = $data['text'];
    $total = $data['total'];
    $uri = $data['route.uri'];

    $slugs = [];

    if ($data['route.variables']) {
        foreach ($data['route.variables'] as $key => $value) {
            $slugs[$key] = $value;
        }
    }

    $options = [];

    for($i = 1; $i <= $pages; $i++) {
        $options[$app['route']->uri($uri, $slugs + ['page' => $i])] = $i;
    }
?>

<div class="pagination">
    <?php if ($data['subtext']): ?>
        <div class="pagination-subtext --margin-top-large --width-full">
            <i>
                <?= $data['!subtext'] ?>
            </i>
        </div>
    <?php endif; ?>

    <div class="pagination-details">
        <div class="pagination-controls --margin-top-large">
            <?php
                $previous = $page < 1;

                if ($previous < 1) {
                    $previous = false;
                }
            ?>

            <?php if (!$previous): ?>
                <div class="--not-allowed">
                    <div class="pagination-previous button button--large button--primary button--text button--white --disabled">
                        <div class="icon icon--rotate180">
                            <?= $app['svg']('arrow-head') ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <a class="pagination-previous button button--large button--primary button--text button--white" href='<?= $app['route']->uri($uri, $slugs + ['page' => $previous]) ?>'>
                    <div class="icon icon--rotate180">
                        <?= $app['svg']('arrow-head') ?>
                    </div>
                </a>
            <?php endif; ?>

            <?= $include('@components/field/select/white', [
                'field' => [
                    'class' => 'pagination-field field--basic field--primary field--white'
                ],
                'field-tag' => [
                    'attributes' => [
                        'name' => 'pagination'
                    ],
                    'directives' => [
                        'change' => 'field-redirect'
                    ]
                ],
                'options' => $options,
                'selected' => $app['route']->uri($uri, $slugs + ['page' => $page]),
                'tooltip-content' => [
                    'class' => 'tooltip-content--scrollbar-small',
                    'direction' => 'n'
                ]
            ]) ?>

            <?php
                $next = $page + 1;

                if ($next > $pages) {
                    $next = false;
                }
            ?>

            <?php if (!$next): ?>
                <div class="--not-allowed">
                    <div class="pagination-next button button--large button--primary button--text button--white --disabled">
                        <div class="icon">
                            <?= $app['svg']('arrow-head') ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <a class="pagination-next button button--large button--primary button--text button--white" href='<?= $app['route']->uri($uri, $slugs + ['page' => $next]) ?>'>
                    <div class="icon">
                        <?= $app['svg']('arrow-head') ?>
                    </div>
                </a>
            <?php endif; ?>
        </div>

        <div class="pagination-text text-list --margin-top-large">
            <div class="text">
                <b class='--text-truncate'>
                    <?php if ($total > 0): ?>
                        Showing <?= ($page * $limit) - ($limit - 1)  ?>-<?= $total > $limit ? ($page * $limit) : $total ?> of <?= $total ?> <?= $text ?>
                    <?php else: ?>
                        Showing 0 of 0 <?= $text ?>
                    <?php endif; ?>
                </b>
            </div>
            <div class="text">
                <span class='--text-small --text-truncate'>
                    Page <?= $page ?> of <?= $pages > 0 ? $pages : 1 ?>
                </span>
            </div>
        </div>
    </div>
</div>
