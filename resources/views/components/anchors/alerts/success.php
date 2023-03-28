<?= $include('default', [
    'key' => 'success',
    'messages' => $app['flash']->getSuccessMessages(),
    'modifier' => 'green'
]) ?>
