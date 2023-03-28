<?php
    $layout('@layouts/master/default');

    $links = [
        [
            'active' => true,
            'frame' => 'details',
            'text' => 'Details'
        ]
    ];
?>

<div class='header-spacer'></div>

<div class='container'>
    <section class='section section--margin-top-large --flex-horizontal-space-between'>
        <div class='page-header'>
            <h1 class='page-header-title'>
                <?= $data['platform.id'] ? 'Editing' : 'Creating' ?> Platform
            </h1>

            <?php if ($data['platform.id']): ?>
                <span class="page-header-subtitle">
                    <b class='--text-black'><?= $data['platform.name'] ?></b>
                </span>
            <?php endif; ?>
        </div>
    </section>

    <section class='section section--margin-top-large'>
        <?= $include('@components/link/scroller/border-grey', compact('links')) ?>
    </section>

    <div class="frames">
        <form
            action="<?= $app['route']->uri('admincp.platform.' . ($data['platform.id'] ? 'update' : 'create') . '.command', ['id' => $data['platform.id']]) ?>"
            class='frame --active --flex-horizontal-space-between'
            data-submit="processing"
            enctype="multipart/form-data"
            id='frame-details'
            method="post"
        >
            <div class="columns --flex-column-reverse-1200px">
                <div class="column column--padding-right column--width-fill column--width-full-1200px">
                    <section class="section section--margin-top --flex-horizontal-space-between" id='scrollto-details'>
                        <div class="page-header">
                            <h3 class="page-header-title --text-crop-top">
                                Details
                            </h3>
                        </div>

                        <?= $include('@components/field/input/default', [
                            'field' => [
                                'class' => 'field--border field--primary --margin-top-large --width-half'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => 'name',
                                    'required' => true,
                                    'value' => $data['platform.name']
                                ]
                            ],
                            'field-title' => [
                                'text' => 'Name'
                            ]
                        ]) ?>

                        <?= $include('@components/field/input/default', [
                            'field' => [
                                'class' => 'field--border field--primary --margin-top-large --width-half'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => 'slug',
                                    'value' => $data['platform.slug']
                                ]
                            ],
                            'field-title' => [
                                'text' => 'Slug'
                            ]
                        ]) ?>

                        <?= $include('@components/field/select/border', [
                            'field' => [
                                'class' => 'field--primary --margin-top --width-full'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => 'account'
                                ]
                            ],
                            'field-title' => [
                                'text' => 'User Game Account'
                            ],
                            'options' => $app['platform']->getAccountOptions(),
                            'selected' => $data['platform.account'],
                            'tooltip-content' => [
                                'direction' => 'sw'
                            ]
                        ]) ?>

                        <?= $include('@components/field/select/border', [
                            'field' => [
                                'class' => 'field--primary --margin-top-large --width-full'
                            ],
                            'field-description' => [
                                'text' => 'Used in view files to create elements using the brand color of the game'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => 'view',
                                    'required' => true
                                ]
                            ],
                            'field-title' => [
                                'text' => 'View Key'
                            ],
                            'options' => $app['platform']->getViewOptions(),
                            'selected' => $data['platform.view'],
                            'tooltip-content' => [
                                'direction' => 'sw'
                            ]
                        ]) ?>
                    </section>

                    <section class="section section--margin-top --border-dashed --border-small --border-top --flex-horizontal-space-between" id='scrollto-save'>
                        <div class="button-group --flex-horizontal-space-between --margin-top --width-full">
                            <?php if ($data['platform.id']): ?>
                                <?php
                                    $app['modal']->add('@components/modals/delete', [
                                        'action' => $app['route']->uri('admincp.platform.delete.command', ['id' => $data['platform.id']]),
                                        'key' => 'platform',
                                        'value' => $data['platform.name']
                                    ]);
                                ?>

                                <div class="button-group-item button button--large button--black button--width --margin-top --width-half-600px" data-click="modal" data-modal="delete">Delete Platform</div>
                            <?php else: ?>
                                <div></div>
                            <?php endif; ?>

                            <button class="button-group-item button button--large button--primary button--width --margin-top --width-half-600px">Save Changes</button>
                        </div>
                    </section>
                </div>

                <div class="column column--padding-left column--width-fixed column--width-full-1200px">
                    <section class="section section--margin-top">
                        <div class="card button--border-dashed button--border-small --background-grey --border-default --width-full">
                            <div class="link-menu link-menu--padding --background-grey">
                                <span class="link link--button-menu link--button-grey link--text --width-full" data-click="scrollto" data-scrollto="details">Details</span>
                                <span class="link link--button-menu link--button-grey link--text --width-full" data-click="scrollto" data-scrollto="save">Save Changes</span>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </form>
</div>
