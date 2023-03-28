<?php
    $layout('@layouts/master/default');

    $app['modal']->add('@components/account/modals/update-password');
?>

<div class="header-spacer header-spacer--full"></div>

<div class="container">
    <form action="<?= $app['route']->uri('account.update.profile.command') ?>" data-submit="processing" enctype="multipart/form-data" method="post">
        <div class="profile-banner" id='upload-banner' style="background-image: url(<?= $app['upload']->path('user.banner', $app['auth']->getBanner()) ?>);">
            <?= $include('@components/field/upload/overlay', [
                'field-tag' => [
                    'attributes' => [
                        'name' => 'banner'
                    ]
                ],
                'onchange' => true,
                'tooltip-content' => [
                    'text' => 'Upload Banner'
                ]
            ]) ?>

            <div class="profile-avatar" id='upload-avatar' style="background-image: url(<?= $app['upload']->path('user.avatar', $app['auth']->getAvatar()) ?>)">
                <?= $include('@components/field/upload/overlay', [
                    'field-tag' => [
                        'attributes' => [
                            'name' => 'avatar'
                        ]
                    ],
                    'onchange' => true,
                    'tooltip-content' => [
                        'text' => 'Upload Avatar'
                    ]
                ]) ?>
            </div>
        </div>
    </form>

    <form action="<?= $app['route']->uri('account.update.profile.command') ?>" class='profile-container' data-submit="processing" method="post">
        <section class='profile-header'>
            <section class='profile-header-section --button-small-right --button-small-left-800px --text-center-800px'>
                <h1 class="--text-crop-both">
                    <?= $app['auth']->getUsername() ?>
                </h1>
            </section>

            <section class="profile-header-section profile-header-section--slug">
                <p class=" --button-small-right --button-small-left-800px --text-center-800px --text-crop-both">
                    @<?= $app['auth']->getSlug() ?>
                </p>
            </section>

            <section class="profile-header-section">
                <div class="group --flex-horizontal-800px --margin-top">
                    <?php $stats = $app['user']->toStatTextListArray($data['ranks'], $app['auth']->toArray()); ?>

                    <?php foreach ($stats as $stat): ?>
                        <div class="group-item --icon-left --margin-top">
                            <div class="icon --absolute-vertical-left">
                                <?= $app['svg']($stat['svg']) ?>
                            </div>

                            <span class="text"><?= $stat['title'] ?>&nbsp;</span>

                            <?php if (is_scalar($stat['text'])): ?>
                                <b class='text'><?= $stat['text'] ?></b>
                            <?php else: ?>
                                <div class="text-group">
                                    <?php foreach ($stat['text'] as $text): ?>
                                        <b class='text'><?= $text ?></b>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class='profile-header-nav'>
                <?= $include('@components/link/scroller/border-grey', [
                    'links' => [
                        [
                            'active' => true,
                            'text' => 'Edit Account'
                        ],
                        [
                            'modal' => 'update-password',
                            'text' => "Update Password"
                        ]
                    ],
                    'scroller-content-wrapper' => [
                        'class' => '--flex-horizontal-800px'
                    ]
                ]) ?>
            </section>
        </section>

        <section class="section section--margin-top">
            <div class="page-header">
                <h3 class="page-header-title --text-crop-both">Your Details</h3>
            </div>

            <div class="--flex-horizontal-space-between --width-full">
                <?= $include('@components/field/input/default', [
                    'field' => [
                        'class' => 'field--border field--primary --margin-top-large --width-half'
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'name' => 'email',
                            'value' => $app['auth']->getEmail()
                        ]
                    ],
                    'field-title' => [
                        'text' => 'Email'
                    ]
                ]) ?>

                <?= $include('@components/field/select/border', [
                    'field' => [
                        'class' => 'field--primary --margin-top-large --width-half'
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'name' => 'timezone'
                        ]
                    ],
                    'field-title' => [
                        'text' => 'Timezone'
                    ],
                    'options' => $app['time']->toIdentifierArray(),
                    'selected' => $app['auth']->getTimezone(),
                    'tooltip-content' => [
                        'direction' => 'sw'
                    ]
                ]) ?>

                <?= $include('@components/field/textarea/default', [
                    'field' => [
                        'class' => 'field--border field--primary --margin-top-large --width-full'
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'name' => 'bio',
                            'value' => $app['auth']->getBio()
                        ],
                        'directives' => [
                            'keydown' => 'field-autoresize'
                        ],
                    ],
                    'field-title' => [
                        'text' => 'Short Bio'
                    ]
                ]) ?>

                <?= $include('@components/field/switch/button-border', [
                    'field' => [
                        'class' => 'field--primary --margin-top-large --width-full',
                        'size' => 'tab'
                    ],
                    'field-description' => [
                        'text' => 'By enabling wagers you can turn any free to play ladder or league match into a wager match.'
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'checked' => $app['auth']->getWagers(),
                            'name' => 'wagers'
                        ]
                    ],
                    'field-title' => [
                        'text' => 'Enable Wagers'
                    ]
                ]) ?>
            </div>
        </section>

        <section class="section section--margin-top">
            <div class="page-header">
                <h3 class="page-header-title --text-crop-both">Your Social Links</h3>
            </div>

            <div class='--flex-horizontal-space-between --width-full'>
                <?php foreach ($app['user']->getSocialAccountOptions() as $key => $title): ?>
                    <?php $section->start('field-text.html') ?>
                        <div class="field-text-button field-text-button--left">
                            <div class='icon icon--large --text-white'>
                                <?= $app['svg']("social/{$key}") ?>
                            </div>
                        </div>
                    <?php $section->end() ?>

                    <?php $section->start('account.field.social') ?>
                        <?= $include('@components/field/input/default', [
                            'field' => [
                                'class' => "field--border field--{$key} --margin-top-large --width-half"
                            ],
                            'field-mask' => [
                                'class' => 'field-mask--button-left'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => $key,
                                    'value' => '{value}'
                                ]
                            ],
                            'field-text' => [
                                'html' => $section('field-text.html')
                            ],
                            'field-title' => [
                                'text' => $title
                            ]
                        ]) ?>
                    <?php $section->end() ?>

                    <?php $accounts = $app['auth']->getAccountsByName($key) ?>

                    <?php if (!count($accounts)): ?>
                        <?= str_replace('{value}', '', $section('account.field.social')) ?>
                    <?php endif; ?>

                    <?php foreach ($accounts as $account): ?>
                        <?= str_replace('{value}', $account['value'], $section('account.field.social')) ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="section section--margin-top">
            <div class="page-header">
                <h3 class="page-header-title --text-crop-both">Your Game Id's</h3>
            </div>

            <div class='--flex-horizontal-space-between --width-full'>
                <?php foreach ($app['user']->getGameAccountOptions() as $key => $title): ?>
                    <?php $section->start('field-text.html') ?>
                        <div class="field-text-button field-text-button--left">
                            <div class='icon icon--large --text-white'>
                                <?= $app['svg']("platform/{$key}") ?>
                            </div>
                        </div>
                    <?php $section->end() ?>

                    <?php $section->start('account.field.game') ?>
                        <?= $include('@components/field/input/default', [
                            'field' => [
                                'class' => "field--border field--{$key} --margin-top-large --width-half"
                            ],
                            'field-mask' => [
                                'class' => 'field-mask--button-left'
                            ],
                            'field-tag' => [
                                'attributes' => [
                                    'name' => $key,
                                    'value' => '{value}'
                                ]
                            ],
                            'field-text' => [
                                'html' => $section('field-text.html')
                            ],
                            'field-title' => [
                                'text' => $title
                            ]
                        ]) ?>
                    <?php $section->end() ?>

                    <?php $accounts = $app['auth']->getAccountsByName($key) ?>

                    <?php if (!count($accounts)): ?>
                        <?= str_replace('{value}', '', $section('account.field.game')) ?>
                    <?php endif; ?>

                    <?php foreach ($accounts as $account): ?>
                        <?= str_replace('{value}', $account['value'], $section('account.field.game')) ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="section section--margin-top-large --flex-end">
            <button class="button button--large button--primary button--width">Save Changes</button>
        </section>
    </form>
</div>
