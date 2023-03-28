<?php
    $layout('@layouts/master/default');

    $positions = [
        0 => 'Remove From Staff'
    ];

    foreach ($data['positions'] as $position) {
        $positions[$position['id']] = $position['name'];
    }
?>

<div class='header-spacer'></div>

<div class='container --max-width-600px'>
    <section class='section section--margin-top-large'>
        <div class='page-header --text-center'>
            <h1 class='page-header-title'>
                Manage Admin
            </h1>
        </div>
    </section>

    <section class='section section--margin-top-large'>
        <?= $include('@components/link/scroller/border-grey', [
            'links' => [
                [
                    'active' => true,
                    'frame' => 'admin',
                    'text' => 'Admin'
                ],
                ($app['auth']->can('manageAdminPositions') ? [
                    'frame' => 'admin-positions',
                    'text' => 'Admin Positions'
                ] : []),
                ($app['auth']->can('manageAdminPositions') ? [
                    'href' => $app['route']->uri('admincp.admin.position.create'),
                    'text' => 'Create Admin Position'
                ] : [])
            ],
            'scroller-content-wrapper' => [
                'class' => 'scroller-content-wrapper--center'
            ]
        ]) ?>

        <div class="frames">
            <div class="frame --active" id='frame-admin'>
                <?php if ($app['auth']->can('manageAdmin')): ?>
                    <form action="<?= $app['route']->uri('admincp.admin.update.command') ?>" class='--max-width-600px' data-submit="processing" method="post">
                        <section class="section section--margin-top">
                            <div class="card --background-grey --border-dashed --border-small --width-full">
                                <div class="card-section card-section--large">
                                    <section class="section">
                                        <?= $include('@components/field/select/border', [
                                            'field' => [
                                                'class' => 'field--primary --width-full'
                                            ],
                                            'field-tag' => [
                                                'attributes' => [
                                                    'name' => 'adminPosition',
                                                    'required' => true
                                                ]
                                            ],
                                            'field-title' => [
                                                'text' => 'Staff Position'
                                            ],
                                            'options' => $positions,
                                            'selected' => $data['adminPosition'],
                                            'tooltip-content' => [
                                                'direction' => 'sw'
                                            ]
                                        ]) ?>

                                        <?php $section->start('container') ?>
                                            <div class="--width-full">
                                                <div class="button-group" id='{id}'>
                                                </div>
                                            </div>
                                        <?php $section->end() ?>

                                        <?php $section->start('template') ?>
                                            <div class="button-group-item button button--black button--large tooltip --margin-top-small" data-click='remove' data-hover='tooltip'>
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

                                        <?= $include('@components/field/input/default', [
                                            'field' => [
                                                'class' => 'field--border field--primary --margin-top-large --width-full'
                                            ],
                                            'field-description' => [
                                                'text' => 'Press enter to add user id'
                                            ],
                                            'field-tag' => [
                                                'attributes' => [
                                                    'placeholder' => 'Add User ID',
                                                    'type' => 'number'
                                                ],
                                                'directives' => [
                                                    'keydown' => 'field-tags',
                                                    'field-tags-container' => 'users',
                                                    'field-tags-template' => $app['html']->escape(str_replace('{name}', 'users[]', $section('template')))
                                                ]
                                            ],
                                            'field-title' => [
                                                'text' => 'User IDs'
                                            ]
                                        ]) ?>

                                        <?= str_replace('{id}', 'users', $section('container')) ?>
                                    </section>

                                    <section class="section section--margin-top-small --flex-end">
                                        <button class="button button--large button--primary">Save Changes</button>
                                    </section>
                                </div>
                            </div>
                        </section>
                    </form>
                <?php endif; ?>

                <?php if ($app['auth']->can('manageAdmin')): ?>
                    <section class="section section--margin-top">
                        <div class="table">
                            <div class="table-header table-header--more-right --background-black-500 --text-white">
                                <span class="table-item table-item--width-fill">
                                    Active Admin
                                </span>
                            </div>

                            <?php foreach ($data['admin'] as $user): ?>
                                <div class="table-row table-row--more-right --background-grey-300 --margin-top-border">
                                    <div class="table-item table-item--width-fill">
                                        <div class="text-list">
                                            <div class="text">
                                                <a href="<?= $app['route']->uri('profile', ['slug' => $user['slug']]) ?>" class="link link--color link--primary --inline --text-bold" target="_blank"><?= $user['username'] ?></a>
                                            </div>
                                            <span class="text --text-small"><?= $positions[$user['adminPosition']] ?></span>
                                        </div>
                                    </div>

                                    <input class='copy' id="copy-<?= $user['id'] ?>" type="number" value='<?= $user['id'] ?>'>

                                    <div class="button button--clear button--large button--more button--white tooltip --absolute-vertical-right --border-color-override --border-grey --border-left" data-click='copy' data-copy='<?= $user['id'] ?>' data-hover="tooltip">
                                        <div class="icon">
                                            <?= $app['svg']('copy') ?>
                                        </div>

                                        <span class="tooltip-content tooltip-content--message tooltip-content--ne">Copy User ID</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>
            </div>

            <?php if ($app['auth']->can('manageAdminPositions')): ?>
                <div class="frame" id='frame-admin-positions'>
                    <section class="section section--margin-top">
                        <div class="table">
                            <div class="table-header table-header--more-right --background-black-500 --text-white">
                                <span class="table-item table-item--width-fill">
                                    Admin Positions
                                </span>
                            </div>

                            <?php foreach ($data['positions'] as $position): ?>
                                <div class="table-row table-row--more-right --background-grey-300 --margin-top-border">
                                    <div class="table-item table-item--width-fill">
                                        <b class="text --image-small-height --flex-vertical">
                                            <?= $position['name'] ?>
                                        </b>
                                    </div>

                                    <a
                                        class="button button--clear button--large button--more button--white tooltip --absolute-vertical-right --border-color-override --border-grey --border-left"
                                        data-hover="tooltip"
                                        href='<?= $app['route']->uri('admincp.admin.position.edit', ['id' => $position['id']]) ?>'
                                    >
                                        <div class="icon">
                                            <?= $app['svg']('settings') ?>
                                        </div>

                                        <span class="tooltip-content tooltip-content--message tooltip-content--ne">Edit Admin Position</span>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>
