<?= $include('default', [
    'border' => [
        'class' => 'border--grey-500'
    ],
    'field' => [
        'class' => "field--border {$data['field.class']}",
        'directives' => $data['field.directives']
    ],
    'link' => [
        'class' => "link--button-grey link--button-menu link--text {$data['link.class']}"
    ],
    'link-menu' => [
        'class' => "--background-grey {$data['link-menu.class']}"
    ],
    'link-title' => [
        'class' => "link-title--button-menu {$data['link-title.class']}"
    ],
    'scrollbar' => [
        'class' => "--background-border-default {$data['scrolbar.class']}"
    ],
    'tooltip-content' => [
        'class' => "--border-small {$data['tooltip-content.class']}",
        'direction' => $data['tooltip-content.direction']
    ]
]) ?>
