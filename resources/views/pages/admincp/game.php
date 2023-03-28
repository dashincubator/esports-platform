<?php
    $layout('@layouts/master/default');

    $links = [
        [
            'active' => true,
            'frame' => 'details',
            'text' => 'Details'
        ]
    ];

    if ($data['ladder.id']) {
        $game = $app['game']->get($data['ladder.game']);
        $platform = $app['platform']->get($game['platform']);

        $links[] = [
            'frame' => 'payout',
            'text' => 'Payout'
        ];
    }
?>

<div class='header-spacer'></div>

<div class='container'>
    <section class='section section--margin-top-large --flex-horizontal-space-between'>
        <div class='page-header'>
            <h1 class='page-header-title'>
                <?= $data['game.id'] ? 'Editing' : 'Creating' ?> Game
            </h1>

            <?php if ($data['game.id']): ?>
                <span class="page-header-subtitle">
                    <b class='--text-black'><?= $data['game.name'] ?></b> For <b class='--text-black'><?= $app['platform']->get($data['game.platform'])['name'] ?></b>
                </span>
            <?php endif; ?>
        </div>
    </section>

    <section class='section section--margin-top-large'>
        <?= $include('@components/link/scroller/border-grey', compact('links')) ?>
    </section>

    <div class="frames">
        <form
            action="<?= $app['route']->uri('admincp.game.' . ($data['game.id'] ? 'update' : 'create') . '.command', ['id' => $data['game.id']]) ?>"
            class='frame --active --flex-horizontal-space-between'
            data-submit="processing"
            enctype="multipart/form-data"
            id='frame-details'
            method="post"
        >
            <div class="columns --flex-column-reverse-1200px">
                <div class="column column--padding-right column--width-fill column--width-full-1200px">
                    <?php
                        $platforms = [];

                        foreach ($app['platform']->getAll() as $platform) {
                            $platforms[$platform['id']] = $platform['name'];
                        }
                    ?>

                    <section class="section section--margin-top --flex-horizontal-space-between" id='scrollto-details'>
                        <div class="page-header">
                            <h3 class="page-header-title --text-crop-top">
                                Details
                            </h3>
                        </div>

                        <?php if ($data['game.id']): ?>
                            <div class="--margin-top-large --not-allowed --width-full">
                                <div class="--disabled">
                        <?php endif; ?>

                            <?= $include('@components/field/select/border', [
                                'field' => [
                                    'class' => 'field--primary --width-full ' . ($data['game.id'] ? '' : ' --margin-top-large')
                                ],
                                'field-tag' => [
                                    'attributes' => [
                                        'name' => 'platform',
                                        'required' => true
                                    ]
                                ],
                                'field-title' => [
                                    'text' => 'Platform'
                                ],
                                'options' => $platforms,
                                'selected' => $data['game.platform'],
                                'tooltip-content' => [
                                    'direction' => 'sw'
                                ]
                            ]) ?>

                        <?php if ($data['game.id']): ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?= $include('@components/field/input/default', [
                            'field' => [
                                'class' => 'field--border field--primary  --margin-top-large --width-half'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => 'name',
                                    'required' => true,
                                    'value' => $data['game.name']
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
                            'field-description' => [
                                'text' => 'Name used in url ( If left blank the game name is used )'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => 'slug',
                                    'value' => $data['game.slug']
                                ]
                            ],
                            'field-title' => [
                                'text' => 'Slug'
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
                            'options' => $app['game']->getViewOptions(),
                            'selected' => $data['game.view'],
                            'tooltip-content' => [
                                'direction' => 'sw'
                            ]
                        ]) ?>

                        <?php if ($data['game.id']): ?>
                            <?= $include('@components/field/upload/default', [
                                'field' => [
                                    'class' => 'field--primary --margin-top-large --width-half'
                                ],
                                'field-tag' => [
                                    'attributes' => [
                                        'name' => 'banner'
                                    ]
                                ],
                                'field-title' => [
                                    'text' => 'Banner'
                                ]
                            ]) ?>

                            <?= $include('@components/field/upload/default', [
                                'field' => [
                                    'class' => 'field--primary --margin-top-large --width-half'
                                ],
                                'field-tag' => [
                                    'attributes' => [
                                        'name' => 'card'
                                    ]
                                ],
                                'field-title' => [
                                    'text' => 'Card'
                                ]
                            ]) ?>
                        <?php endif; ?>
                    </section>

                    <section class="section section--margin-top --border-dashed --border-small --border-top --flex-horizontal-space-between" id='scrollto-save'>
                        <div class="button-group --flex-horizontal-space-between --margin-top --width-full">
                            <?php if ($data['game.id']): ?>
                                <?php
                                    $app['modal']->add('@components/modals/delete', [
                                        'action' => $app['route']->uri('admincp.game.delete.command', ['id' => $data['game.id']]),
                                        'key' => 'game',
                                        'value' => $data['game.name']
                                    ]);
                                ?>

                                <div class="button-group-item button button--large button--black button--width --margin-top --width-half-600px" data-click="modal" data-modal="delete">Delete Game</div>
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
</div>
