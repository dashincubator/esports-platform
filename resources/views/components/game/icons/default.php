<?php
    $class = $data['class'];
    $game = $data['game'];
    $platform = $app['platform']->get($game['platform']);
    $small = $data['small'];
?>

<div class="game-icons <?= $class ?>">
    <div class="button button--circle <?= $small ? 'button--small' : '' ?> button--static button--<?= $platform['view'] ?>">
        <div class="icon <?= $small ? '' : 'icon--large' ?>">
            <?= $app['svg']('platform/' . $platform['view']) ?>
        </div>
    </div>

    <div class="game-icon-game button button--circle <?= $small ? 'button--small' : '' ?> button--static button--<?= $game['view'] ?>">
        <div class="icon <?= $small ? '' : 'icon--large' ?>">
            <?= $app['svg']('game/' . $game['view']) ?>
        </div>
    </div>
</div>
