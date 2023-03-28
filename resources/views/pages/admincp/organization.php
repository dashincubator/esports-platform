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
    <section class='section section--margin-top-large'>
        <div class='page-header'>
            <h1 class='page-header-title'>
                <?= $data['organization.id'] ? 'Editing' : 'Creating' ?> Organization
            </h1>

            <?php if ($data['organization.id']): ?>
                <div class="page-header-subtitle">
                    Editing <?= $data['organization.name'] ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class='section section--margin-top-large'>
        <?= $include('@components/link/scroller/border-grey', compact('links')) ?>
    </section>

    <div class="frames">
        <form
            action="<?= $app['route']->uri('admincp.organization.' . ($data['organization.id'] ? 'update' : 'create') . '.command', ['id' => $data['organization.id']]) ?>"
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
                            Organization Details
                        </h3>
                    </div>

                    <?= $include('@components/field/input/default', [
                        'field' => [
                            'class' => 'field--border field--primary --background-grey --margin-top-large --width-full'
                        ],
                        'field-tag' => [
                            'attributes' => [
                                'name' => 'name',
                                'required' => true,
                                'value' => $data['organization.name']
                            ]
                        ],
                        'field-title' => [
                            'text' => 'Name'
                        ]
                    ]) ?>

                    <?= $include('@components/field/input/default', [
                        'field' => [
                            'class' => 'field--border field--primary --background-grey --margin-top-large --width-half'
                        ],
                        'field-tag' => [
                            'attributes' => [
                                'name' => 'domain',
                                'required' => true,
                                'value' => $data['organization.domain']
                            ]
                        ],
                        'field-title' => [
                            'text' => 'Domain'
                        ]
                    ]) ?>

                    <?= $include('@components/field/input/default', [
                        'field' => [
                            'class' => 'field--border field--primary --background-grey --margin-top-large --width-half'
                        ],
                        'field-tag' => [
                            'attributes' => [
                                'name' => 'paypal',
                                'value' => $data['organization.paypal']
                            ]
                        ],
                        'field-title' => [
                            'text' => 'Paypal Email'
                        ]
                    ]) ?>

                    <?= $include('@components/field/input/default', [
                        'field' => [
                            'class' => 'field--border field--primary --background-grey --margin-top-large --width-half'
                        ],
                        'field-tag' => [
                            'attributes' => [
                                'name' => 'user',
                                'type' => 'number',
                                'value' => $data['organization.user']
                            ]
                        ],
                        'field-title' => [
                            'text' => 'Admin User ID'
                        ]
                    ]) ?>
                </section>

                <section class="section section--margin-top --border-dashed --border-small --border-top" id='scrollto-save'>
                    <div class="button-group --flex-horizontal-space-between --margin-top --width-full">
                        <?php if ($data['organization.id']): ?>
                            <?php
                                $app['modal']->add('@components/modals/delete', [
                                    'action' => $app['route']->uri('admincp.organization.delete.command', ['id' => $data['organization.id']]),
                                    'key' => 'organization',
                                    'value' => $data['organization.name']
                                ]);
                            ?>

                            <div class="button-group-item button button--large button--black button--width --margin-top --width-half-600px" data-click="modal" data-modal="delete">Delete Organization</div>
                        <?php else: ?>
                            <div></div>
                        <?php endif; ?>

                        <button class="button-group-item button button--large button--primary button--width --margin-top --width-half-600px">Save Changes</button>
                    </div>
                </section>
            </div>

            <div class="column column--padding-left column--width-fixed column--width-full-1200px"></div>
        </form>
    </div>
</div>
