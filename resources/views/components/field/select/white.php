<?= $include('default', [
    'border' => [
        'class' => 'border--grey'
    ],
    'field' => [
        'class' => "field--white {$data['field.class']}"
    ],
    'link' => [
        'class' => "link--button-grey link--button-menu link--text {$data['link.class']}"
    ],
    'link-menu' => [
        'class' => "--background-white {$data['link-menu.class']}"
    ],
    'link-title' => [
        'class' => "link-title--button-menu {$data['link-title.class']}"
    ],
    'scrollbar' => [
        'class' => $data['scrolbar.class']
    ],
    'tooltip-content' => [
        'class' => $data['tooltip-content.class'],
        'direction' => $data['tooltip-content.direction']
    ]
]) ?>
