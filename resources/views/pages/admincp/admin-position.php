<?php $layout('@layouts/master/default') ?>

<div class='header-spacer'></div>

<div class='container'>
    <section class='section section--margin-top-large'>
        <div class='page-header'>
            <h1 class='page-header-title'>
                <?= $data['position.id'] ? 'Editing' : 'Creating' ?> Admin Position
            </h1>

            <?php if ($data['position.id']): ?>
                <span class="page-header-subtitle">
                    <b class='--text-black'><?= $data['position.name'] ?></b>
                </span>
            <?php endif; ?>
        </div>
    </section>

    <section class='section section--margin-top-large'>
        <div class="columns --flex-column-reverse-1200px">
            <div class="column column--padding-right column--width-fill column--width-full-1200px">
                <form
                    action="<?= $app['route']->uri('admincp.admin.position.' . ($data['position.id'] ? 'update' : 'create') . '.command', ['id' => $data['position.id']]) ?>"
                    class='--flex-horizontal-space-between'
                    data-submit="processing"
                    method="post"
                >
                    <section class="section section--margin-top">
                        <?= $include('@components/field/input/default', [
                            'field' => [
                                'class' => 'field--border field--primary --background-grey --width-full'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => 'name',
                                    'required' => true,
                                    'value' => $data['position.name']
                                ]
                            ],
                            'field-title' => [
                                'text' => 'Name'
                            ]
                        ]) ?>
                    </section>

                    <?php
                        $sections = [
                            'game' => [
                                'description' => 'The following settings allow the admin to perform actions within the games selected above.',
                                'permissions' => [
                                    [
                                        'title' => 'Manages Ladders',
                                        'description' => 'Can create, edit, or delete ladders.',
                                        'value' => 'manageLadders'
                                    ],
                                    [
                                        'title' => 'Manages Ladder Gametypes',
                                        'description' => 'Can create, edit, or delete ladder gametypes.',
                                        'value' => 'manageLadderGametypes'
                                    ]
                                ]
                            ],
                            'general' => [
                                'permissions' => [
                                    [
                                        'title' => 'Manages Admin',
                                        'description' => 'Can add, or remove admin.',
                                        'value' => 'manageAdmin'
                                    ],
                                    [
                                        'title' => 'Manages Admin Positions',
                                        'description' => 'Can create, edit, and delete admin positions.',
                                        'value' => 'manageAdminPositions'
                                    ],
                                    [
                                        'title' => 'Manages Bank Withdraws',
                                        'description' => 'Can mark bank withdraws as paid.',
                                        'value' => 'manageBankWithdraws'
                                    ],
                                    [
                                        'title' => 'Manages Games',
                                        'description' => 'Can create, edit, or delete games and platforms.',
                                        'value' => 'manageGames'
                                    ],
                                    [
                                        'title' => 'Manages Organizations',
                                        'description' => 'Can create, edit, or delete organizations.',
                                        'value' => 'manageOrganizations'
                                    ],
                                    [
                                        'title' => 'Owner',
                                        'description' => 'Can view dashboards etc.',
                                        'value' => 'owner'
                                    ]
                                ]
                            ]
                        ];
                    ?>

                    <section class="section section--margin-top --border-dashed --border-small --border-top" id='scrollto-game'>
                        <div class="page-header">
                            <h3 class="page-header-title --text-crop-top">
                                Game Permissions
                            </h3>
                            <span class="page-header-subtitle">
                                Select all games this position will be managing.
                            </span>
                        </div>

                        <div class="card --border-dashed --border-small --margin-top-large --width-full">
                            <?php $games = $data['position.games'] ? $data['position.games']->toArray() : []; ?>

                            <?php foreach($app['game']->getAll() as $game): ?>
                                <label class="button button--large button--basic button--transparent field field--primary --background-grey --width-full" data-change='field-checkbox'>
                                    <div class="field-check field-check--checkmark field-check--right">
                                        <div class="text-list --game-icons-small-left">
                                            <?= $include('@components/game/icons/trigger', [
                                                'class' => '--absolute-vertical-left',
                                                'game' => $game,
                                                'small' => true
                                            ]) ?>

                                            <b class="text">
                                                <?= $game['name'] ?>
                                            </b>
                                            <div class="text --text-small">
                                                <span class="--text-truncate">
                                                    <?= $app['platform']->get($game['platform'])['name'] ?>
                                                </span>
                                            </div>
                                        </div>

                                        <span class="field-mask field-mask--switch">
                                            <input class="field-tag" type="checkbox" name='games[]' data-ref='trigger:change' value="<?= $game['id'] ?>" <?= in_array($game['id'], $games) ? 'checked' : '' ?>>
                                        </span>
                                    </div>
                                </label>

                                <div class="border border--dashed border--small"></div>
                            <?php endforeach; ?>
                        </div>

                        <?= $include('components/permissions', $sections['game']) ?>
                    </section>

                    <section class="section section--margin-top --border-dashed --border-small --border-top" id='scrollto-general'>
                        <div class="page-header">
                            <h3 class="page-header-title --text-crop-top">
                                General Permissions
                            </h3>
                            <span class="page-header-subtitle">
                                The following settings are super-admin like permissions.
                            </span>
                        </div>

                        <?= $include('components/permissions', $sections['general']) ?>
                    </section>

                    <section class="section section--margin-top --border-dashed --border-small --border-top --flex-horizontal-space-between" id='scrollto-save'>
                        <div class="button-group --flex-horizontal-space-between --margin-top --width-full">
                            <?php if ($data['position.id']): ?>
                                <?php
                                    $app['modal']->add('@components/modals/delete', [
                                        'action' => $app['route']->uri('admincp.admin.position.delete.command', ['id' => $data['position.id']]),
                                        'key' => 'admin position',
                                        'value' => $data['position.name']
                                    ]);
                                ?>

                                <div class="button-group-item button button--large button--black button--width --margin-top --width-half-600px" data-click="modal" data-modal="delete">Delete Position</div>
                            <?php else: ?>
                                <div></div>
                            <?php endif; ?>

                            <button class="button-group-item button button--large button--primary button--width --margin-top --width-half-600px">Save Changes</button>
                        </div>
                    </section>
                </form>
            </div>

            <div class="column column--padding-left column--width-fixed column--width-full-1200px">
                <section class="section section--margin-top">
                    <div class="card button--border-dashed button--border-small --background-grey --border-default --width-full">
                        <div class="link-menu link-menu--padding --background-grey">
                            <span class="link link--button-menu link--button-grey link--text --width-full" data-click="scrollto" data-scrollto="game">Game Permissions</span>
                            <span class="link link--button-menu link--button-grey link--text --width-full" data-click="scrollto" data-scrollto="general">General Permissions</span>
                            <span class="link link--button-menu link--button-grey link--text --width-full" data-click="scrollto" data-scrollto="save">Save Changes</span>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>
