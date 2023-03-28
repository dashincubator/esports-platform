<?php
    $app['modal']->add('@components/event/team/modals/create', [
        'action' => $data['action'],
        'title' => $data['title']
    ]);
?>

<div class='button button--primary group-item --margin-top-small' data-click='modal' data-modal='team-create'><?= $data['button.text'] ?></div>
