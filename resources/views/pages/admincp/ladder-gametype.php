<?php
    $layout('@layouts/master/default');

    $links = [
        [
            'active' => true,
            'frame' => 'details',
            'text' => 'Details'
        ]
    ];

    if ($data['gametype.id']) {
        $game = $app['game']->get($data['gametype.game']);
        $platform = $app['platform']->get($game['platform']);
    }
?>

<div class='header-spacer'></div>

<div class='container'>
    <section class='section section--margin-top-large'>
        <div class='page-header'>
            <h1 class='page-header-title'>
                <?= $data['gametype.id'] ? 'Editing' : 'Creating' ?> Ladder Gametype
            </h1>

            <?php if ($data['gametype.id']): ?>
                <div class="page-header-subtitle">
                    Editing <?= "{$platform['name']} {$game['name']} {$data['gametype.name']} Gametype" ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class='section section--margin-top-large'>
        <?= $include('@components/link/scroller/border-grey', compact('links')) ?>
    </section>

    <div class="frames">
        <form
            action="<?= $app['route']->uri('admincp.ladder.gametype.' . ($data['gametype.id'] ? 'update' : 'create') . '.command', ['id' => $data['gametype.id']]) ?>"
            class='frame --active --flex-horizontal-space-between'
            data-submit="processing"
            id="frame-details"
            method="post"
        >
        <div class="columns --flex-column-reverse-1200px">
            <div class="column column--padding-right column--width-fill column--width-full-1200px">
                <section class="section section--margin-top --flex-horizontal-space-between" id='scrollto-details'>
                    <div class="page-header">
                        <h3 class="page-header-title --text-crop-top">
                            Gametype Details
                        </h3>
                    </div>

                    <?php
                        $games = [];

                        foreach ($data['games'] as $game) {
                            $games[$game] = $app['game']->get($game)['name'];
                        }
                    ?>

                    <?php if ($data['gametype.id']): ?>
                        <div class="--margin-top-large --not-allowed --width-full">
                            <div class="--disabled">
                    <?php endif; ?>

                        <?= $include('@components/field/select/border', [
                            'field' => [
                                'class' => 'field--primary --width-full' . ($data['gametype.id'] ? '' : ' --margin-top-large')
                            ],
                            'field-mask' => [
                                'class' => '--background-grey'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => 'game',
                                    'required' => true
                                ]
                            ],
                            'field-title' => [
                                'text' => 'Game'
                            ],
                            'options' => $games,
                            'selected' => $data['gametype.game'],
                            'tooltip-content' => [
                                'direction' => 's'
                            ]
                        ]) ?>

                    <?php if ($data['gametype.id']): ?>
                            </div>
                        </div>
                    <?php endif; ?>


                    <?= $include('@components/field/input/default', [
                        'field' => [
                            'class' => 'field--border field--primary --margin-top-large --width-full'
                        ],
                        'field-tag' => [
                            'attributes' => [
                                'name' => 'name',
                                'required' => true,
                                'value' => $data['gametype.name']
                            ]
                        ],
                        'field-title' => [
                            'text' => 'Name'
                        ]
                    ]) ?>
                </section>

                <?php $section->start('container') ?>
                    <div class="--width-full">
                        <div class="button-group" id='{id}'>
                            {values}
                        </div>
                    </div>
                <?php $section->end() ?>

                <?php $section->start('template') ?>
                    <div class="button-group-item button button--black button--large tooltip --margin-top" data-click='remove' data-hover='tooltip'>
                        <div class="--icon-small-right">
                            {value}
                            <div class="icon icon--small --absolute-vertical-right">
                                <?= $app['svg']('close') ?>
                            </div>
                        </div>

                        <input name="{name}" type="hidden" value="{value}">

                        <span class="tooltip-content tooltip-content--message tooltip-content--nw">Remove</span>
                    </div>
                <?php $section->end() ?>

                <section class="section section--margin-top --border-dashed --border-small --border-top" id='scrollto-bestof'>
                    <div class="page-header">
                        <h3 class="page-header-title --text-crop-top">
                            Best Of
                        </h3>
                        <span class="page-header-subtitle">
                            Total number of maps teams will play per match.
                            Each option will be available to users in the ladder matchfinder.
                        </span>
                    </div>

                    <?= $include('@components/field/input/default', [
                            'field' => [
                                'class' => 'field--border field--primary --margin-top-large --width-full'
                            ],
                            'field-description' => [
                                'text' => 'Press enter to add option'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'placeholder' => 'Add Best Of Option',
                                    'type' => 'number'
                                ],
                                'directives' => [
                                    'keydown' => 'field-tags',
                                    'field-tags-container' => 'bestof',
                                    'field-tags-template' => $app['html']->escape(str_replace('{name}', 'bestOf[]', $section('template')))
                                ]
                            ]
                        ]) ?>

                        <?php if ($data['gametype.id']): ?>
                            <?php $section->start('bestOf') ?>
                                <?php foreach ($data['gametype.bestOf'] as $bestOf): ?>
                                    <?= str_replace(['{name}', '{value}'], ['bestOf[]', $bestOf], $section('template')) ?>
                                <?php endforeach; ?>
                            <?php $section->end() ?>
                        <?php endif; ?>

                        <?= str_replace(['{id}', '{values}'], ['bestof', $section('bestOf')], $section('container')) ?>
                </section>

                <?php $section->start('append-template') ?>
                    <div class='card --border-dashed --border-small --margin-top --width-full' id='append-{id}'>
                        <div class="card-section">
                            <div class="--flex-end --width-full">
                                <div class="button button--black button--circle button--small tooltip" data-click='remove' data-hover='tooltip' data-remove='append-{id}'>
                                    <div class="icon icon--small">
                                        <?= $app['svg']('close') ?>
                                    </div>

                                    <span class="tooltip-content tooltip-content--message tooltip-content--w">Remove Mapset Group</span>
                                </div>
                            </div>

                            <?= $include('@components/field/input/default', [
                                'field' => [
                                    'class' => 'field--border field--primary --margin-top --width-full'
                                ],
                                'field-tag' => [
                                    'attributes' => [
                                        'name' => 'mapsets[{id}][gametype]',
                                        'required' => true,
                                        'placeholder' => 'Gametype Name: Search and Destroy, etc.',
                                        'value' => '{gametype}'
                                    ]
                                ]
                            ]) ?>

                            <?= $include('@components/field/input/default', [
                                'field' => [
                                    'class' => 'field--primary --margin-top-small --width-full'
                                ],
                                'field-description' => [
                                    'text' => 'Press enter to add option'
                                ],
                                'field-tag' => [
                                    'attributes' => [
                                        'placeholder' => 'Add Gametype Map'
                                    ],
                                    'directives' => [
                                        'keydown' => 'field-tags',
                                        'field-tags-container' => 'mapsets-{id}',
                                        'field-tags-template' => $app['html']->escape(str_replace('{name}', 'mapsets[{id}][maps][]', $section('template')))
                                    ]
                                ]
                            ]) ?>

                            <?= str_replace('{id}', 'mapsets-{id}', $section('container')) ?>
                        </div>
                    </div>
                <?php $section->end() ?>

                <section class="section section--margin-top --border-dashed --border-small --border-top" id='scrollto-mapsets'>
                    <div class="page-header">
                        <h3 class="page-header-title --text-crop-top">
                            Mapsets
                        </h3>
                        <span class="page-header-subtitle">
                            Write description
                        </span>
                    </div>

                    <div class="--flex-end --width-full">
                        <div class="--width-full" id='append-mapsets'>
                            <?php if ($data['gametype.mapsets']): ?>
                                <?php $id = time(); ?>

                                <?php foreach ($data['gametype.mapsets'] as $gametype => $maps): ?>
                                    <?php $id++ ?>

                                    <?php $section->start('gametypes') ?>
                                        <?php foreach ($maps as $map): ?>
                                            <?= str_replace(['{name}', '{value}'], ["mapsets[{$id}][maps][]", $map], $section('template')) ?>
                                        <?php endforeach; ?>
                                    <?php $section->end() ?>

                                    <?= str_replace(['{gametype}', '{id}', '{values}'], [$gametype, $id, $section('gametypes')], $section('append-template')) ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <b class="link link--color link--primary --inline --margin-top --text-small" data-append-container='append-mapsets' data-append-template='<?= $app['html']->escape(str_replace(['{gametype}', '{values}'], '', $section('append-template'))) ?>' data-click='append'>
                            Add New Tier
                        </b>
                    </div>
                </section>

                <section class="section section--margin-top --border-dashed --border-small --border-top" id='scrollto-modifiers'>
                    <div class="page-header">
                        <h3 class="page-header-title --text-crop-top">
                            Modifiers
                        </h3>
                        <span class="page-header-subtitle">
                            Write description
                        </span>
                    </div>

                    <?= $include('@components/field/input/default', [
                        'field' => [
                            'class' => 'field--border field--primary --margin-top-large --width-full'
                        ],
                        'field-description' => [
                            'text' => 'Press enter to add option'
                        ],
                        'field-tag' => [
                            'attributes' => [
                                'placeholder' => 'Add Modifier Option'
                            ],
                            'directives' => [
                                'keydown' => 'field-tags',
                                'field-tags-container' => 'modifiers',
                                'field-tags-template' => $app['html']->escape(str_replace('{name}', 'modifiers[]', $section('template')))
                            ]
                        ]
                    ]) ?>

                    <?php if ($data['gametype.id']): ?>
                        <?php $section->start('modifiers') ?>
                            <?php foreach ($data['gametype.modifiers'] as $modifier): ?>
                                <?= str_replace(['{name}', '{value}'], ['modifiers[]', $modifier], $section('template')) ?>
                            <?php endforeach; ?>
                        <?php $section->end() ?>
                    <?php endif; ?>

                    <?= str_replace(['{id}', '{values}'], ['modifiers', $section('modifiers')], $section('container')) ?>
                </section>

                <section class="section section--margin-top --border-dashed --border-small --border-top" id='scrollto-players'>
                    <div class="page-header">
                        <h3 class="page-header-title --text-crop-top">
                            Players Per Team
                        </h3>
                        <span class="page-header-subtitle">
                            Write description
                        </span>
                    </div>

                    <?= $include('@components/field/input/default', [
                        'field' => [
                            'class' => 'field--border field--primary --margin-top-large --width-full'
                        ],
                        'field-description' => [
                            'text' => 'Press enter to add option'
                        ],
                        'field-tag' => [
                            'attributes' => [
                                'placeholder' => 'Add Players Per Team Option',
                                'type' => 'number'
                            ],
                            'directives' => [
                                'keydown' => 'field-tags',
                                'field-tags-container' => 'playersPerTeam',
                                'field-tags-template' => $app['html']->escape(str_replace('{name}', 'playersPerTeam[]', $section('template')))
                            ]
                        ]
                    ]) ?>

                    <?php if ($data['gametype.id']): ?>
                        <?php $section->start('playersPerTeam') ?>
                            <?php foreach ($data['gametype.playersPerTeam'] as $playersPerTeam): ?>
                                <?= str_replace(['{name}', '{value}'], ['playersPerTeam[]', $playersPerTeam], $section('template')) ?>
                            <?php endforeach; ?>
                        <?php $section->end() ?>
                    <?php endif; ?>

                    <?= str_replace(['{id}', '{values}'], ['playersPerTeam', $section('playersPerTeam')], $section('container')) ?>
                </section>

                <section class="section section--margin-top --border-dashed --border-small --border-top" id='scrollto-teams'>
                    <div class="page-header">
                        <h3 class="page-header-title --text-crop-top">
                            Teams Per Match
                        </h3>
                        <span class="page-header-subtitle">
                            Write description
                        </span>
                    </div>

                    <?= $include('@components/field/input/default', [
                        'field' => [
                            'class' => 'field--border field--primary --margin-top-large --width-full'
                        ],
                        'field-description' => [
                            'text' => 'Press enter to add option'
                        ],
                        'field-tag' => [
                            'attributes' => [
                                'placeholder' => 'Add Teams Per Match Option',
                                'type' => 'number'
                            ],
                            'directives' => [
                                'keydown' => 'field-tags',
                                'field-tags-container' => 'teamsPerMatch',
                                'field-tags-template' => $app['html']->escape(str_replace('{name}', 'teamsPerMatch[]', $section('template')))
                            ]
                        ]
                    ]) ?>

                    <?php if ($data['gametype.id']): ?>
                        <?php $section->start('teamsPerMatch') ?>
                            <?php foreach ($data['gametype.teamsPerMatch'] as $teamsPerMatch): ?>
                                <?= str_replace(['{name}', '{value}'], ['teamsPerMatch[]', $teamsPerMatch], $section('template')) ?>
                            <?php endforeach; ?>
                        <?php $section->end() ?>
                    <?php endif; ?>

                    <?= str_replace(['{id}', '{values}'], ['teamsPerMatch', $section('teamsPerMatch')], $section('container')) ?>
                </section>

                <section class="section section--margin-top --border-dashed --border-small --border-top" id='scrollto-save'>
                    <div class="button-group --flex-horizontal-space-between --margin-top --width-full">
                        <?php if ($data['gametype.id']): ?>
                            <?php
                                $app['modal']->add('@components/modals/delete', [
                                    'action' => $app['route']->uri('admincp.ladder.gametype.delete.command', ['id' => $data['gametype.id']]),
                                    'key' => 'gametype',
                                    'value' => $data['gametype.name']
                                ]);
                            ?>

                            <div class="button-group-item button button--large button--black button--width --margin-top --width-half-600px" data-click="modal" data-modal="delete">Delete Gametype</div>
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
                            <span class="link link--button-menu link--button-grey link--text --width-full" data-click="scrollto" data-scrollto="bestof">Best Of</span>
                            <span class="link link--button-menu link--button-grey link--text --width-full" data-click="scrollto" data-scrollto="mapsets">Mapsets</span>
                            <span class="link link--button-menu link--button-grey link--text --width-full" data-click="scrollto" data-scrollto="modifiers">Modifiers</span>
                            <span class="link link--button-menu link--button-grey link--text --width-full" data-click="scrollto" data-scrollto="players">Players Per Team</span>
                            <span class="link link--button-menu link--button-grey link--text --width-full" data-click="scrollto" data-scrollto="teams">Teams Per Match</span>
                            <span class="link link--button-menu link--button-grey link--text --width-full" data-click="scrollto" data-scrollto="save">Save Changes</span>
                        </div>
                    </div>
                </section>
            </div>
        </form>
    </div>
</div>
