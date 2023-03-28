<?php
    $layout('@layouts/master/default');

    $links = [
        [
            'active' => true,
            'frame' => 'details',
            'text' => 'Details'
        ]
    ];

    $games = [];

    foreach ($data['games'] as $id) {
        $game = $app['game']->get($id);

        $games[$game['id']] = $app['platform']->get($game['platform'])['name'] . ' ' . $game['name'];
    }

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
                <?= $data['ladder.id'] ? "{$data['ladder.name']}" : 'Creating Ladder' ?>
            </h1>

            <?php if ($data['ladder.id']): ?>
                <div class="page-header-subtitle">
                    Editing <?= "{$platform['name']} {$game['name']} " . ucwords($data['ladder.type']) ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class='section section--margin-top-large'>
        <?= $include('@components/link/scroller/border-grey', compact('links')) ?>
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

    <div class="frames">
        <form
            action="<?= $app['route']->uri('admincp.ladder.' . ($data['ladder.id'] ? 'update' : 'create') . '.command', ['id' => $data['ladder.id']]) ?>"
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
                                Event Details
                            </h3>
                        </div>

                        <?php if ($data['ladder.id']): ?>
                            <div class="--margin-top-large --not-allowed --width-full">
                                <div class="--disabled">
                        <?php endif; ?>

                            <?= $include('@components/field/select/border', [
                                'field' => [
                                    'class' => 'field--primary --width-full' . ($data['ladder.id'] ? '' : ' --margin-top-large'),
                                    'directives' => [
                                        'change' => 'game-select'
                                    ]
                                ],
                                'field-mask' => [
                                    'class' => '--background-grey'
                                ],
                                'field-tag' => [
                                    'attributes' => [
                                        'name' => 'game',
                                        'required' => true
                                    ],
                                    'directives' => [
                                        'ref' => 'trigger:change'
                                    ]
                                ],
                                'field-title' => [
                                    'text' => 'Game'
                                ],
                                'options' => $games,
                                'selected' => $data['ladder.game'],
                                'tooltip-content' => [
                                    'direction' => 's'
                                ]
                            ]) ?>

                        <?php if ($data['ladder.id']): ?>
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
                                    'value' => $data['ladder.name']
                                ],
                                'class' => '--background-grey'
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
                                'text' => 'M/D/Y Time'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => 'startsAt',
                                    'placeholder' => date('m/d/Y g:i a', time()),
                                    'required' => true,
                                    'value' => ($data['ladder.startsAt'] ? date('m/d/Y g:i a', $data['ladder.startsAt']) : '')
                                ],
                                'class' => '--background-grey'
                            ],
                            'field-title' => [
                                'text' => 'Start Date'
                            ]
                        ]) ?>

                        <?= $include('@components/field/input/default', [
                            'field' => [
                                'class' => 'field--border field--primary --margin-top-large --width-half'
                            ],
                            'field-description' => [
                                'text' => 'M/D/Y Time'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => 'endsAt',
                                    'placeholder' => date('m/d/Y g:i a', time()),
                                    'required' => true,
                                    'value' => ($data['ladder.endsAt'] ? date('m/d/Y g:i a', $data['ladder.endsAt']) : '')
                                ],
                                'class' => '--background-grey'
                            ],
                            'field-title' => [
                                'text' => 'End Date'
                            ]
                        ]) ?>

                        <?php if ($data['ladder.id']): ?>
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

                    <section class="section section--margin-top --border-dashed --border-small --border-top --flex-horizontal-space-between" id='scrollto-entry'>
                        <div class="page-header">
                            <h3 class="page-header-title --text-crop-top">
                                Entry
                            </h3>
                            <span class="page-header-subtitle">
                                Write description
                            </span>
                        </div>

                        <?= $include('@components/field/input/default', [
                            'field' => [
                                'class' => 'field--border field--primary --margin-top-large --width-half'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => 'entryFee',
                                    'required' => true,
                                    'step' => 0.1,
                                    'type' => 'number',
                                    'value' => ($data['ladder.entryFee'] ?? 0)
                                ],
                                'class' => '--background-grey'
                            ],
                            'field-title' => [
                                'text' => 'Entry Fee Per Player'
                            ]
                        ]) ?>

                        <?= $include('@components/field/select/border', [
                            'field' => [
                                'class' => 'field--primary --margin-top-large --width-half'
                            ],
                            'field-mask' => [
                                'class' => '--background-grey'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => 'membershipRequired',
                                    'required' => true
                                ]
                            ],
                            'field-title' => [
                                'text' => 'Membership'
                            ],
                            'options' => [
                                0 => 'Not Required',
                                1 => 'Required'
                            ],
                            'selected' => $data['ladder.membershipRequired'],
                            'tooltip-content' => [
                                'direction' => 'sw'
                            ]
                        ]) ?>

                        <?php $section->start('append-template') ?>
                            <div class='card --border-dashed --border-small --margin-top --width-full' id='append-{id}'>
                                <div class="card-section --flex-horizontal-space-between">
                                    <div class="--flex-end --width-full">
                                        <div class="button button--black button--circle button--small tooltip" data-click='remove' data-hover='tooltip' data-remove='append-{id}'>
                                            <div class="icon icon--small">
                                                <?= $app['svg']('close') ?>
                                            </div>

                                            <span class="tooltip-content tooltip-content--message tooltip-content--w">Remove Group</span>
                                        </div>
                                    </div>

                                    <?= $include('@components/field/input/default', [
                                        'field' => [
                                            'class' => 'field--border field--primary --margin-top --width-half'
                                        ],
                                        'field-tag' => [
                                            'attributes' => [
                                                'name' => 'entryFeePrizes[{id}][key]',
                                                'required' => true,
                                                'placeholder' => 'Key',
                                                'value' => '{key}'
                                            ]
                                        ]
                                    ]) ?>

                                    <?= $include('@components/field/input/default', [
                                        'field' => [
                                            'class' => 'field--border field--primary --margin-top --width-half'
                                        ],
                                        'field-description' => [
                                            'text' => 'Use Decimal Format ( 80% = 0.8 )'
                                        ],
                                        'field-tag' => [
                                            'attributes' => [
                                                'name' => 'entryFeePrizes[{id}][value]',
                                                'required' => true,
                                                'min' => 0.01,
                                                'max' => 1,
                                                'placeholder' => 'Percentage Paid To This Tier',
                                                'step' => 0.01,
                                                'type' => 'number',
                                                'value' => '{value}'
                                            ]
                                        ]
                                    ]) ?>
                                </div>
                            </div>
                        <?php $section->end() ?>

                        <div class="--flex-end --width-full">
                            <div class="--width-full" id='append-entry'>
                                <?php if ($data['ladder.entryFeePrizes']): ?>
                                    <?php $id = time(); ?>

                                    <?php foreach ($data['ladder.entryFeePrizes'] as $place => $percentage): ?>
                                        <?php $id++ ?>

                                        <?= str_replace(['{id}', '{key}', '{value}'], [$id, $place, $percentage], $section('append-template')) ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <b class="link link--color link--primary --inline --margin-top --text-small" data-append-container='append-entry' data-append-template='<?= $app['html']->escape(str_replace(['{key}'], '', $section('append-template'))) ?>' data-click='append'>
                                Add New Tier
                            </b>
                        </div>
                    </section>

                    <section class="section section--margin-top --border-dashed --border-small --border-top --flex-horizontal-space-between" id='scrollto-competition'>
                        <div class="page-header">
                            <h3 class="page-header-title --text-crop-top">
                                Competition Format
                            </h3>
                            <span class="page-header-subtitle">
                                Define additional competition rules and restrictions.
                            </span>
                        </div>

                        <?= $include('@components/field/input/default', [
                            'field' => [
                                'class' => 'field--border field--primary --margin-top-large --width-half'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => 'minPlayersPerTeam',
                                    'required' => true,
                                    'step' => 1,
                                    'type' => 'number',
                                    'value' => ($data['ladder.minPlayersPerTeam'] ?? 1)
                                ],
                                'class' => '--background-grey'
                            ],
                            'field-title' => [
                                'text' => 'Min Players Per Team'
                            ]
                        ]) ?>

                        <?= $include('@components/field/input/default', [
                            'field' => [
                                'class' => 'field--border field--primary --margin-top-large --width-half'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => 'maxPlayersPerTeam',
                                    'required' => true,
                                    'step' => 1,
                                    'type' => 'number',
                                    'value' => ($data['ladder.maxPlayersPerTeam'] ?? 1)
                                ],
                                'class' => '--background-grey'
                            ],
                            'field-title' => [
                                'text' => 'Max Players Per Team'
                            ]
                        ]) ?>

                        <?= $include('@components/field/select/border', [
                            'field' => [
                                'class' => 'field--primary --margin-top-large --width-full'
                            ],
                            'field-mask' => [
                                'class' => '--background-grey'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => 'format'
                                ]
                            ],
                            'field-title' => [
                                'text' => 'Matchfinder Mode'
                            ],
                            'options' => $app['ladder']->getFormatOptions(),
                            'selected' => $data['ladder.format'],
                            'tooltip-content' => [
                                'direction' => 's'
                            ]
                        ]) ?>

                        <?= $include('@components/field/input/default', [
                            'field' => [
                                'class' => 'field--border field--primary --margin-top-large --width-half'
                            ],
                            'field-description' => [
                                'text' => 'First team to reach this score wins'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => 'firstToScore',
                                    'required' => true,
                                    'step' => 1,
                                    'type' => 'number',
                                    'value' => ($data['ladder.firstToScore'] ?? 0)
                                ],
                                'class' => '--background-grey'
                            ],
                            'field-title' => [
                                'text' => 'First To Score'
                            ]
                        ]) ?>

                        <?= $include('@components/field/input/default', [
                            'field' => [
                                'class' => 'field--border field--primary --margin-top-large --width-half'
                            ],
                            'field-description' => [
                                'text' => 'Team cannot schedule a match after x losses'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => 'stopLoss',
                                    'required' => true,
                                    'step' => 1,
                                    'type' => 'number',
                                    'value' => ($data['ladder.stopLoss'] ?? 0)
                                ],
                                'class' => '--background-grey'
                            ],
                            'field-title' => [
                                'text' => 'Stop Loss'
                            ]
                        ]) ?>
                    </section>

                    <section class="section section--margin-top --border-dashed --border-small --border-top" id='scrollto-matchfinder'>
                        <div class="page-header">
                            <h3 class="page-header-title --text-crop-top">
                                Matchfinder Gametype Options
                            </h3>
                            <span class="page-header-subtitle">
                                Select all gametypes that should be available in the matchfinder.
                            </span>
                        </div>

                        <?php
                            $gametypes = [];

                            foreach ($data['gametypes'] as $gametype) {
                                $gametypes[$gametype['game']][] = $gametype;
                            }

                            foreach ($games as $id => $option) {
                                if (array_key_exists($id, $gametypes)) {
                                    continue;
                                }

                                $gametypes[$id] = [];
                            }

                            $selected = [];

                            if ($data['ladder.gametypes']) {
                                $selected = $data['ladder.gametypes']->toArray();
                            }
                        ?>

                        <?php foreach ($gametypes as $key => $list): ?>
                            <div data-click='frame' data-frame='gametype-<?= $key ?>' id='frame-trigger-gametype-<?= $key ?>'></div>

                            <div class="frame" id='frame-gametype-<?= $key ?>'>
                                <?php if (!count($list)): ?>
                                    <div class="card --background-grey --border-dashed --border-small --margin-top-large --width-full">
                                        <div class="card-section --text-center">
                                            No <b><?= $games[$key] ?></b> Gametypes Found
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="card --border-dashed --border-small --margin-top-large">
                                        <?php foreach ($list as $item): ?>
                                            <label class="button button--tab button--basic button--grey field field--primary --width-full" data-change='field-checkbox'>
                                                <div class="field-check field-check--checkmark field-check--right">
                                                    <b class="field-title"><?= $item['name'] ?></b>

                                                    <span class="field-mask">
                                                        <input class="field-tag" type="checkbox" name='gametypes[<?= $key ?>][]' data-ref='trigger:change' value="<?= $item['id'] ?>" <?= in_array($item['id'], $selected) ? 'checked' : '' ?>>
                                                    </span>
                                                </div>
                                            </label>

                                            <div class="border border--dashed border--small"></div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </section>

                    <section class="section section--margin-top --border-dashed --border-small --border-top --flex-horizontal-space-between" id='scrollto-prizes'>
                        <div class="page-header">
                            <h3 class="page-header-title --text-crop-top">
                                Prizes
                            </h3>
                            <span class="page-header-subtitle">
                                The prize list order used here will dictate the order used on the prizes tab.
                            </span>
                        </div>

                        <?php $section->start('append-template') ?>
                            <div class='card --background-grey --border-dashed --border-small --margin-top-small --width-full' id='append-{id}'>
                                <div class="card-section --flex-horizontal-space-between">
                                    <div class="--flex-end --width-full">
                                        <div class="button button--black button--circle button--small tooltip" data-click='remove' data-hover='tooltip' data-remove='append-{id}'>
                                            <div class="icon icon--small">
                                                <?= $app['svg']('close') ?>
                                            </div>

                                            <span class="tooltip-content tooltip-content--message tooltip-content--w">Remove Group</span>
                                        </div>
                                    </div>

                                    <?= $include('@components/field/input/default', [
                                        'field' => [
                                            'class' => 'field--border field--primary'
                                        ],
                                        'field-tag' => [
                                            'attributes' => [
                                                'name' => 'prizes[{id}][key]',
                                                'required' => true,
                                                'placeholder' => 'Place',
                                                'value' => '{place}'
                                            ]
                                        ]
                                    ]) ?>

                                    <?= $include('@components/field/input/default', [
                                        'field' => [
                                            'class' => 'field--primary --margin-top-small --width-full'
                                        ],
                                        'field-description' => [
                                            'text' => 'Press enter to add prize item'
                                        ],
                                        'field-tag' => [
                                            'attributes' => [
                                                'placeholder' => 'Prize List Item'
                                            ],
                                            'directives' => [
                                                'keydown' => 'field-tags',
                                                'field-tags-container' => 'prizes-{id}',
                                                'field-tags-template' => $app['html']->escape(str_replace('{name}', 'prizes[{id}][values][]', $section('template')))
                                            ]
                                        ]
                                    ]) ?>

                                    <?= str_replace('{id}', 'prizes-{id}', $section('container')) ?>
                                </div>
                            </div>
                        <?php $section->end() ?>

                        <?= $include('@components/field/select/border', [
                            'field' => [
                                'class' => 'field--primary --margin-top-large --width-half'
                            ],
                            'field-mask' => [
                                'class' => '--background-grey'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => 'prizesAdjusted',
                                    'required' => true
                                ]
                            ],
                            'field-title' => [
                                'text' => 'Are Prizes Set In Stone?'
                            ],
                            'options' => [
                                0 => 'Prizes Will Not Change',
                                1 => 'Prizes Will Change If We Have Low Team Count'
                            ],
                            'selected' => $data['ladder.prizesAdjusted'],
                            'tooltip-content' => [
                                'direction' => 'sw'
                            ]
                        ]) ?>

                        <?= $include('@components/field/input/default', [
                            'field' => [
                                'class' => 'field--border field--primary --margin-top-large --width-half'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => 'prizePool',
                                    'required' => true,
                                    'step' => 0.1,
                                    'type' => 'number',
                                    'value' => ($data['ladder.prizePool'] ?? 0)
                                ],
                                'class' => '--background-grey'
                            ],
                            'field-title' => [
                                'text' => 'Base Prize Pool'
                            ]
                        ]) ?>

                        <div class="--flex-end --width-full">
                            <div class="--width-full" id='append-prizes'></div>

                            <b class="link link--color link--primary --inline --margin-top --text-small" data-append-container='append-prizes' data-append-template='<?= $app['html']->escape(str_replace(['{place}', '{values}'], '', $section('append-template'))) ?>' data-click='append'>
                                Add New Tier
                            </b>
                        </div>

                        <?php if ($data['ladder.prizes']): ?>
                            <?php $id = time(); ?>

                            <?php foreach ($data['ladder.prizes'] as $place => $prizes): ?>
                                <?php $id++ ?>

                                <?php $section->start('prizes') ?>
                                    <?php foreach ($prizes as $prize): ?>
                                        <?= str_replace(['{name}', '{value}'], ["prizes[{$id}][values][]", $prize], $section('template')) ?>
                                    <?php endforeach; ?>
                                <?php $section->end() ?>

                                <?= str_replace(['{id}', '{place}', '{values}'], [$id, $place, $section('prizes')], $section('append-template')) ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </section>

                    <section class="section section--margin-top --border-dashed --border-small --border-top" id='scrollto-rules'>
                        <div class="page-header">
                            <h3 class="page-header-title --text-crop-top">
                                Rules
                            </h3>
                            <span class="page-header-subtitle">
                                The list list order used here will dictate the order used on the rules tab.
                            </span>
                        </div>

                        <?php $section->start('append-template') ?>
                            <div class='card --background-grey --border-dashed --border-small --margin-top-small --width-full' id='append-{id}'>
                                <div class="card-section --flex-horizontal-space-between">
                                    <div class="--flex-end --width-full">
                                        <div class="button button--black button--circle button--small tooltip" data-click='remove' data-hover='tooltip' data-remove='append-{id}'>
                                            <div class="icon icon--small">
                                                <?= $app['svg']('close') ?>
                                            </div>

                                            <span class="tooltip-content tooltip-content--message tooltip-content--w">Remove Group</span>
                                        </div>
                                    </div>

                                    <?= $include('@components/field/input/default', [
                                        'field' => [
                                            'class' => 'field--border field--primary --margin-top --width-full'
                                        ],
                                        'field-tag' => [
                                            'attributes' => [
                                                'name' => 'rules[{id}][key]',
                                                'required' => true,
                                                'placeholder' => 'Rule Title',
                                                'value' => '{place}'
                                            ]
                                        ]
                                    ]) ?>

                                    <?= $include('@components/field/input/default', [
                                        'field' => [
                                            'class' => 'field--primary --margin-top-small --width-full'
                                        ],
                                        'field-description' => [
                                            'text' => 'Press enter to add rule item'
                                        ],
                                        'field-tag' => [
                                            'attributes' => [
                                                'placeholder' => 'Rule List Item'
                                            ],
                                            'directives' => [
                                                'keydown' => 'field-tags',
                                                'field-tags-container' => 'rules-{id}',
                                                'field-tags-template' => $app['html']->escape(str_replace('{name}', 'rules[{id}][values][]', $section('template')))
                                            ]
                                        ]
                                    ]) ?>

                                    <?= str_replace('{id}', 'rules-{id}', $section('container')) ?>
                                </div>
                            </div>
                        <?php $section->end() ?>

                        <div class="--flex-end --width-full">
                            <div class="--margin-top-small --width-full" id='append-rules'></div>

                            <b class="link link--color link--primary --inline --margin-top --text-small" data-append-container='append-rules' data-append-template='<?= $app['html']->escape(str_replace(['{place}', '{values}'], '', $section('append-template'))) ?>' data-click='append'>
                                Add New Tier
                            </b>
                        </div>

                        <?php if ($data['ladder.rules']): ?>
                            <?php $id = time(); ?>

                            <?php foreach ($data['ladder.rules'] as $rule): ?>
                                <?php $id++ ?>

                                <?php $section->start('rules') ?>
                                    <?php foreach ($rule['content'] as $content): ?>
                                        <?= str_replace(['{name}', '{value}'], ["rules[{$id}][values][]", $content], $section('template')) ?>
                                    <?php endforeach; ?>
                                <?php $section->end() ?>

                                <?= str_replace(['{id}', '{place}', '{values}'], [$id, $rule['title'], $section('rules')], $section('append-template')) ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </section>

                    <section class="section section--margin-top --border-dashed --border-small --border-top" id='scrollto-save'>
                        <div class="button-group --flex-horizontal-space-between --margin-top --width-full">
                            <?php if ($data['ladder.id']): ?>
                                <?php
                                    $app['modal']->add('@components/modals/delete', [
                                        'action' => $app['route']->uri('admincp.ladder.delete.command', ['id' => $data['ladder.id']]),
                                        'key' => 'ladder',
                                        'value' => $data['ladder.name']
                                    ]);
                                ?>

                                <div class="button-group-item button button--large button--black button--width --margin-top --width-half-600px" data-click="modal" data-modal="delete">Delete <?= ucfirst($data['ladder.type']) ?></div>
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
                                <span class="link link--button-menu link--button-grey link--text --width-full" data-click="scrollto" data-scrollto="details">Event Details</span>
                                <span class="link link--button-menu link--button-grey link--text --width-full" data-click="scrollto" data-scrollto="competition">Competition Format</span>
                                <span class="link link--button-menu link--button-grey link--text --width-full" data-click="scrollto" data-scrollto="matchfinder">Matchfinder Gametype Options</span>
                                <span class="link link--button-menu link--button-grey link--text --width-full" data-click="scrollto" data-scrollto="prizes">Prizes</span>
                                <span class="link link--button-menu link--button-grey link--text --width-full" data-click="scrollto" data-scrollto="rules">Rules</span>
                                <span class="link link--button-menu link--button-grey link--text --width-full" data-click="scrollto" data-scrollto="save">Save Changes</span>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </form>

        <?php if ($data['ladder.id']): ?>
            <form
                action="<?= $app['route']->uri('admincp.ladder.payout.command', ['id' => $data['ladder.id']]) ?>"
                class='frame --flex-horizontal-space-between'
                data-submit="processing"
                enctype="multipart/form-data"
                id='frame-payout'
                method="post"
            >
                <?php $section->start('append-template') ?>
                    <div class='card --background-grey --border-dashed --border-small --margin-top-small --width-full' id='append-{id}'>
                        <div class="card-section --flex-horizontal-space-between">
                            <div class="--flex-end --width-full">
                                <div class="button button--black button--circle button--small tooltip" data-click='remove' data-hover='tooltip' data-remove='append-{id}'>
                                    <div class="icon icon--small">
                                        <?= $app['svg']('close') ?>
                                    </div>

                                    <span class="tooltip-content tooltip-content--message tooltip-content--w">Remove Group</span>
                                </div>
                            </div>

                            <div class="--margin-top --not-allowed --width-half">
                                <?= $include('@components/field/input/default', [
                                    'field' => [
                                        'class' => 'field--border field--primary --disabled --width-full'
                                    ],
                                    'field-tag' => [
                                        'attributes' => [
                                            'name' => 'payout[{id}][place]',
                                            'required' => true,
                                            'type' => 'number',
                                            'value' => '{place}'
                                        ]
                                    ],
                                    'field-title' => [
                                        'text' => 'Event Placement'
                                    ]
                                ]) ?>
                            </div>

                            <div class="--margin-top --not-allowed --width-half">
                                <?= $include('@components/field/input/default', [
                                    'field' => [
                                        'class' => 'field--border field--primary --disabled --width-full'
                                    ],
                                    'field-tag' => [
                                        'attributes' => [
                                            'name' => 'payout[{id}][team]',
                                            'required' => true,
                                            'type' => 'number',
                                            'value' => '{team}'
                                        ]
                                    ],
                                    'field-title' => [
                                        'text' => 'Team ID'
                                    ]
                                ]) ?>
                            </div>

                            <?= $include('@components/field/input/default', [
                                'field' => [
                                    'class' => 'field--border field--primary --margin-top --width-half'
                                ],
                                'field-description' => [
                                    'text' => 'Enter the total payout given to this team it will be divided evenly between all members of this team'
                                ],
                                'field-tag' => [
                                    'attributes' => [
                                        'name' => 'payout[{id}][amount]',
                                        'required' => true,
                                        'type' => 'number',
                                        'value' => 0
                                    ]
                                ],
                                'field-title' => [
                                    'text' => 'Total Payout Split By Team'
                                ]
                            ]) ?>

                            <?= $include('@components/field/select/border', [
                                'field' => [
                                    'class' => 'field--primary --margin-top --width-half'
                                ],
                                'field-description' => [
                                    'text' => 'Each member will recieve the membership time defined here'
                                ],
                                'field-mask' => [
                                    'class' => '--background-grey'
                                ],
                                'field-tag' => [
                                    'attributes' => [
                                        'name' => 'payout[{id}][membership]',
                                        'required' => true
                                    ]
                                ],
                                'field-title' => [
                                    'text' => 'Give Each Member Of The Team Membership'
                                ],
                                'options' => $app['config']->get('membership.payout.options'),
                                'tooltip-content' => [
                                    'direction' => 'sw'
                                ]
                            ]) ?>
                        </div>
                    </div>
                <?php $section->end() ?>

                <div class="columns">
                    <div class="column column--padding-right column--width-fill column--width-full-1200px">
                        <section class="section section--margin-top" id='prize-append'>
                            <?= $include('@components/field/switch/button-border', [
                                'field' => [
                                    'class' => 'field--primary --background-grey --width-full',
                                    'size' => 'tab'
                                ],
                                'field-description' => [
                                    'text' => "
                                        If enabled users will be able to withdraw the money deposited into their accounts.
                                        If disabled this is treated in a way similar to 'giveaway' money.
                                        This is meant to encourage spending on the website vs being immediately withdrawn.
                                    "
                                ],
                                'field-tag' => [
                                    'attributes' => [
                                        'checked' => true,
                                        'name' => 'withdrawable'
                                    ]
                                ],
                                'field-title' => [
                                    'text' => 'Can Users Withdraw This Money?'
                                ]
                            ]) ?>
                        </section>

                        <section class="section section--margin-top --border-dashed --border-small --border-top --flex-end">
                            <div class="tickets-add-links-center card --background-grey --border-dashed --border-small --flex-center --width-full --absolute-full" data-click='activate' data-activate='ticket-add-links' id='ticket-add-links'>
                                <div class="card-section --flex-vertical --text-center">
                                    <span class='--text-crop-both --width-full'>
                                        <?php if (!$data['ladder.isClosed']): ?>
                                            <b class='--text-primary'>WARNING!</b> <br>
                                            Ladder has not reached the end date, are you editing the correct event? This doubles as misclick protection, click to finalize payout.
                                        <?php else: ?>
                                            <b class='--text-primary'>MISCLICK PROTECTION!</b> <br>
                                            Once payout is complete the ladder is closed. This provides protection against accidental clicks, click to finalize payout.
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>

                            <button class="button button--large button--primary button--width --width-half-600px">Complete Payout</button>
                        </section>
                    </div>

                    <div class="column column--padding-left column--width-fixed column--width-full-1200px">
                        <section class="section section--margin-top">
                            <div class="table">
                                <div class="table-header table-header--more-right --background-black-500 --text-white">
                                    <span class="table-item table-item--width-fill">
                                        Top 32 Teams By Placement
                                    </span>
                                </div>

                                <?php if (!count($data['teams'])): ?>
                                    <?= $include('@components/table/row/empty', [
                                        'text' => 'No teams found'
                                    ]) ?>
                                <?php endif; ?>

                                <?php foreach (($data['teams'] ?? []) as $team): ?>
                                    <?php
                                        if (!is_numeric($team['rank']) || $team['rank'] < 1) {
                                            continue;
                                        }
                                    ?>
                                    <div class="table-row table-row--more-right --background-grey-300 --margin-top-border">
                                        <div class="table-item table-item--width-fill">
                                            <div class="text-list">
                                                <div class="text">
                                                    <a href="<?= $app['route']->uri('ladder.team', [
                                                        'game' => $game['slug'],
                                                        'ladder' => $data['ladder.slug'],
                                                        'platform' => $platform['slug'],
                                                        'team' => $team['slug']
                                                    ]) ?>" class="link link--color link--primary --inline --text-bold" target="_blank">
                                                        <?= $team['name'] ?>
                                                    </a>
                                                </div>
                                                <div class="text --text-small">
                                                    <span>
                                                        Placed
                                                        <b>
                                                            <?= $team['rank'] . $app['ordinal']($team['rank']) ?>
                                                        </b>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="button button--clear button--large button--more button--white tooltip --absolute-vertical-right --border-color-override --border-grey --border-left"  data-append-container='prize-append' data-append-template='<?= $app['html']->escape(str_replace(['{place}', '{team}'], [$team['rank'], $team['id']], $section('append-template'))) ?>' data-click='append' data-hover="tooltip">
                                            <div class="icon">
                                                <?= $app['svg']('plus-circle') ?>
                                            </div>

                                            <span class="tooltip-content tooltip-content--message tooltip-content--ne">Create Payout For Team</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
