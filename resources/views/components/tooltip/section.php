<?php foreach ($data['items'] as $item): ?>
    <div class='tooltip-section --border-small --border-top --border-grey --text-text'>
        <?= $include('@components/text/list', ['items' => [$item]]) ?>
    </div>
<?php endforeach; ?>
