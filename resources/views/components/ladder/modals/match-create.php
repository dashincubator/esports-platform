<div class="modal modal--small" data-modifier='black' id='modal-match-create'>
    <div class="modal-content --background-grey">

        <form action="<?= $app['route']->uri('ladder.match.create.command', $data['slugs']->toArray()) ?>" class='frames' data-submit='processing' method="post">

            <input name="team" type="hidden" value="<?= $data['team.id'] ?>">

            <div class="frame --active" id="frame-match-settings">
                <div class="modal-section">
                    <div class="page-header">
                        <span class="page-header-category --text-red --text-small">Step 1</span>
                        <h3 class="page-header-title">Match Settings</h3>
                        <span class="page-header-subtitle">Select a gametype to adjust the settings required for this match</span>
                    </div>
                </div>

                <div class="modal-section --border-grey-500 --border-small --border-top">
                    <?php
                        $options = [];

                        foreach ($data['gametypes'] as $gametype) {
                            $options[$gametype['id']] = $gametype['name'];
                        }
                    ?>

                    <?= $include('@components/field/select/white', [
                        'field' => [
                            'class' => 'field--primary --width-full'
                        ],
                        'field-tag' => [
                            'attributes' => [
                                'name' => 'gametype',
                                'required' => true
                            ],
                            'directives' => [
                                'ref' => 'trigger:change'
                            ]
                        ],
                        'field-text' => [
                            'directives' => [
                                'change' => 'matchfinder-gametype'
                            ]
                        ],
                        'field-title' => [
                            'text' => 'Gametype'
                        ],
                        'options' => $options,
                        'tooltip-content' => [
                            'direction' => 's'
                        ]
                    ]) ?>

                    <?php foreach ($data['gametypes'] as $index => $gametype): ?>
                        <div class="accordion accordion--noanimation --flex-horizontal-space-between" id='accordion-matchfinder-gametype-<?= $gametype['id'] ?>'>
                            <?php
                                $options = [];

                                foreach ($gametype['bestOf'] as $bestOf) {
                                    $options[$bestOf] = $bestOf;
                                }
                            ?>

                            <?= $include('@components/field/select/white', [
                                'field' => [
                                    'class' => 'field--primary field--white --margin-top --width-half'
                                ],
                                'field-description' => [
                                    'text' => '# of games'
                                ],
                                'field-tag' => [
                                    'attributes' => [
                                        'name' => "settings[{$gametype['id']}][bestOf]",
                                        'required' => true
                                    ]
                                ],
                                'field-title' => [
                                    'text' => 'Best Of'
                                ],
                                'options' => $options,
                                'tooltip-content' => [
                                    'direction' => 's'
                                ]
                            ]) ?>

                            <?= $include('@components/field/input/default', [
                                'field' => [
                                    'class' => 'field--primary field--white --margin-top --width-half'
                                ],
                                'field-description' => [
                                    'text' => '$ per player'
                                ],
                                'field-tag' => [
                                    'attributes' => [
                                        'name' => "settings[{$gametype['id']}][wager]",
                                        'required' => true,
                                        'step' => '1',
                                        'type' => 'number',
                                        'value' => 0
                                    ]
                                ],
                                'field-title' => [
                                    'text' => 'Wager'
                                ]
                            ]) ?>

                            <?php if (count($gametype['teamsPerMatch']) > 1): ?>
                                <?php
                                    $options = [];

                                    foreach ($gametype['teamsPerMatch'] as $teamsPerMatch) {
                                        $options[$teamsPerMatch] = $teamsPerMatch;
                                    }
                                ?>

                                <?php $section->start('field-description.html') ?>
                                    The "<b class="text --text-small --text-red --inline"><?= $gametype['name'] ?></b>" gametype can be played with <b>more than 2 teams</b>
                                <?php $section->end() ?>

                                <?= $include('@components/field/select/white', [
                                    'field' => [
                                        'class' => 'field--primary field--white --margin-top --width-full'
                                    ],
                                    'field-description' => [
                                        'html' => $section('field-description.html')
                                    ],
                                    'field-tag' => [
                                        'attributes' => [
                                            'name' => "settings[{$gametype['id']}][teamsPerMatch]",
                                            'required' => true
                                        ]
                                    ],
                                    'field-title' => [
                                        'text' => 'Total Teams'
                                    ],
                                    'options' => $options,
                                    'tooltip-content' => [
                                        'direction' => 'n'
                                    ]
                                ]) ?>
                            <?php else: ?>
                                <input type="hidden" name='settings[<?= $gametype['id'] ?>][teamsPerMatch]' value='<?= $gametype['teamsPerMatch'][0] ?>'>
                            <?php endif; ?>

                            <div class="--margin-top-small --width-full">
                                <?php if (count($gametype['modifiers']) > 0): ?>
                                    <div class="button button--border-small button--tab --active --background-grey --border-dashed --border-default --flex-horizontal-space-between --margin-top-small --width-full" data-click='accordion' data-accordion='match-modifiers'>
                                        <span>Add Modifiers</span>

                                        <div class="accordion-arrow icon">
                                            <?= $app['svg']('arrow-head') ?>
                                        </div>
                                    </div>

                                    <div class="accordion card --background-grey --border --border-dashed --border-small" id='accordion-match-modifiers'>
                                        <?php foreach ($gametype['modifiers'] as $modifier): ?>
                                            <label class="button button--tab button--basic button--transparent field field--primary --width-full" data-change='field-checkbox'>
                                                <div class="field-check field-check--checkmark field-check--right">
                                                    <span class="field-title"><?= $modifier ?></span>

                                                    <span class="field-mask">
                                                        <input class="field-tag" type="checkbox" name='settings[<?= $gametype['id'] ?>][modifiers][]' data-ref='trigger:change' value="<?= $modifier ?>">
                                                    </span>
                                                </div>
                                            </label>

                                            <div class="border border--dashed border--small"></div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="card --background-grey --border-dashed --border-small --margin-top-small --width-full" data-click='accordion' data-accordion='match-start-time-<?= $gametype['id'] ?>'>
                                    <div class="card-section">
                                        <div class="text --flex-horizontal-space-between --width-full">
                                            <span>
                                                Start Time: <b class='--text-primary'>Available Now</b>
                                            </span>

                                            <div class="accordion-arrow icon">
                                                <?= $app['svg']('arrow-head') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion card --background-grey --border-dashed --border-small --width-full" id='accordion-match-start-time-<?= $gametype['id'] ?>'>
                                    <div class="card-section">
                                        <div class="list --margin-top">
                                            <span class='list-item list-item--bulletpoint list-item--small'>This match will remain active for up to 1 hour or until the match is accepted.</span>
                                            <span class='list-item list-item--bulletpoint list-item--small'>Once the match is accepted it will be scheduled for the closest 10 minute interval.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="--flex-end --margin-top-large --width-full">
                                <div class="button button--black button--large --width-half" data-click='frame' data-frame='match-roster' data-frame-ignore>
                                    Next &nbsp;
                                    <div class="icon">
                                        <?= $app['svg']('arrow') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="frame" id="frame-match-roster">
                <div class="modal-section">
                    <div class="page-header">
                        <span class="page-header-category --text-red --text-small">Step 2</span>
                        <h3 class="page-header-title">Match Roster</h3>
                        <span class="page-header-subtitle --text-crop-bottom">The # of members you select determines the player size of each team</span>
                    </div>

                    <?php foreach ($data['gametypes'] as $index => $gametype): ?>
                        <div class="text-group <?= ($index === 0) ? '--active' : '' ?> --hidden-inactive --margin-top" id='matchfinder-roster-<?= $gametype['id'] ?>'>
                            <?php foreach ($gametype['playersPerTeam'] as $playersPerTeam): ?>
                                <b class="text"><?= $playersPerTeam ?>v<?= $playersPerTeam ?></b>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="modal-section --border-grey-500 --border-small --border-top">
                    <div class="table">
                        <div class="table-header --background-black-500 --text-white">
                            <span class="table-item --button-badge-width">&nbsp;</span>
                            <span class="table-item table-item--padding-left table-item--width-fill">Member</span>
                            <span class="table-item table-item--padding-left table-item--width-small --flex-center">Playing</span>
                        </div>

                        <?php foreach ($data['roster'] as $member): ?>
                            <?php $eligible = $member['eligible']; ?>

                            <div class="table-row --background-grey-300">
                                <div class='table-item --button-badge-width --flex-center'>
                                    <div class="button button--<?= $eligible ? 'green' : 'red' ?> button--circle button--badge tooltip" data-hover="tooltip">
                                        <div class="icon icon--badge">
                                            <?= $app['svg']($eligible ? 'check' : 'close') ?>
                                        </div>

                                        <span class="tooltip-content tooltip-content--message tooltip-content--e">
                                            <?= $eligible ? 'Eligible' : 'Ineligible' ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="table-item table-item--padding-left table-item--width-fill">
                                    <div class="text --flex-vertical --image-large-left --image-large-height">
                                        <img class="image image--large --absolute-vertical-left" src="<?= $app['upload']->path('user.avatar', $member['user.avatar']) ?>">

                                        <div class="text">
                                            <a href="<?= $app['route']->uri('profile', ['slug' => $member['user.slug']]) ?>" class="link link--color link--primary link--text --inline --text-bold --text-truncate" target="_blank">
                                                <?= $member['user.username'] ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-item table-item--padding-left table-item--width-small --flex-center">
                                    <div class="field field--primary" data-change='field-checkbox'>
                                        <label class="field-check field-check--checkmark field-check--checkmark-size">
                                            <span class="field-mask">
                                                <input class="field-tag" name='roster[]' type="checkbox" data-ref='trigger:change' value="<?= $member['user.id'] ?>" <?= !$eligible ? '--disabled' : '' ?>>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="text --margin-top-large --text-small">
                        <div>
                            By clicking create match you agree to follow the
                            <a class='link link--color link--primary --inline --text-bold --text-small' href="<?= $app['route']->uri('legal.terms-of-service') ?>" target="_blank">
                                <?= ucfirst($data['ladder.type']) ?> Rules
                            </a>
                            for this match.
                        </div>
                    </div>

                    <div class="--flex-horizontal-space-between --width-full">
                        <div class="button button--black button--large --margin-top-large --width-half" data-click='frame' data-frame='match-settings' data-frame-ignore>
                            <div class="icon icon--rotate180">
                                <?= $app['svg']('arrow') ?>
                            </div>
                            &nbsp; Back
                        </div>

                        <button class="button button--large button--primary --margin-top-large --width-half">Create Match</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
