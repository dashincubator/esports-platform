<?= $include('default', [
    'key' => 'error',
    'messages' => $app['flash']->getErrorMessages(),
    'modifier' => 'red'
]) ?>
