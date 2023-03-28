<div class="modal modal--small" data-modifier='black' id='modal-sign'>
    <div class="modal-content --background-grey">

        <div class="modal-section page-header --text-center">
            <h2 class="page-header-title">Welcome back!</h2>
            <span class="page-header-subtitle">
                Sign in or create a new account to continue
            </span>
        </div>

        <?= $include('@components/link/scroller/border-grey', [
            'links' => [
                [
                    'active' => !$app['route']->is('account.auth.sign-up'),
                    'color' => 'secondary',
                    'frame' => 'sign-in',
                    'svg' => 'user-circle',
                    'text' => 'Sign In'
                ],
                [
                    'active' => $app['route']->is('account.auth.sign-up'),
                    'frame' => 'sign-up',
                    'svg' => 'plus-circle',
                    'text' => 'New Account'
                ]
            ],
            'scroller-content-wrapper' => [
                'class' => 'scroller-content-wrapper--center --modal-section-padding'
            ]
        ]) ?>

        <div class="frames">
            <form action="<?= $app['route']->uri('account.auth.sign-in.command') ?>" class='frame modal-section <?= !$app['route']->is('account.auth.sign-up') ? '--active' : '' ?>' data-submit='processing' id="frame-sign-in" method="post">
                <?= $include('@components/field/input/default', [
                    'field' => [
                        'class' => 'field--secondary field--white --width-full'
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'name' => 'identifier',
                            'required' => true,
                            'value' => $app['flash']->getInput('identifier')
                        ]
                    ],
                    'field-title' => [
                        'text' => 'Username or Email'
                    ]
                ]) ?>

                <?php $section->start('field-description.html') ?>
                    Forgot password?
                    <b class="link link--color link--secondary --inline --text-small" data-click='modal' data-modal="forgot-password" <?= $app['route']->is('account.auth.forgot-password') ? "data-ref='trigger:click'" : "" ?>>
                        Recover Your Account
                    </b>
                <?php $section->end() ?>

                <?= $include('@components/field/input/default', [
                    'field' => [
                        'class' => 'field--secondary field--white --margin-top --width-full'
                    ],
                    'field-description' => [
                        'html' => $section('field-description.html')
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'name' => 'password',
                            'required' => true,
                            'type' => 'password'
                        ]
                    ],
                    'field-text' => [
                        'html' => $include('@components/field/password-toggle')
                    ],
                    'field-title' => [
                        'text' => 'Password'
                    ]
                ]) ?>

                <button class="button button--large button--secondary --margin-top-large --width-full">Sign In</button>
            </form>

            <form action="<?= $app['route']->uri('account.auth.sign-up.command') ?>" class='frame modal-section <?= $app['route']->is('account.auth.sign-up') ? '--active' : '' ?>' data-submit='processing' id="frame-sign-up" method="post">
                <?= $include('@components/field/input/default', [
                    'field' => [
                        'class' => 'field--primary field--white --width-full'
                    ],
                    'field-description' => [
                        'text' => '3–32 characters'
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'name' => 'username',
                            'required' => true,
                            'value' => $app['flash']->getInput('username')
                        ]
                    ],
                    'field-title' => [
                        'text' => 'Username'
                    ]
                ]) ?>

                <?= $include('@components/field/input/default', [
                    'field' => [
                        'class' => 'field--primary field--white --margin-top --width-full'
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'name' => 'email',
                            'required' => true,
                            'value' => $app['flash']->getInput('email')
                        ]
                    ],
                    'field-title' => [
                        'text' => 'Email'
                    ]
                ]) ?>

                <?= $include('@components/field/select/white', [
                    'field' => [
                        'class' => 'field--primary --margin-top --width-full'
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'name' => 'timezone',
                            'required' => true
                        ]
                    ],
                    'field-title' => [
                        'text' => 'Timezone'
                    ],
                    'options' => $app['time']->toIdentifierArray(),
                    'selected' => $app['flash']->getInput('timezone'),
                    'tooltip-content' => [
                        'direction' => 'c'
                    ]
                ]) ?>

                <?= $include('@components/field/input/default', [
                    'field' => [
                        'class' => 'field--primary field--white --margin-top --width-full'
                    ],
                    'field-description' => [
                        'text' => 'Your password must contain at least one letter'
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'name' => 'password',
                            'required' => true,
                            'type' => 'password'
                        ]
                    ],
                    'field-text' => [
                        'html' => $include('@components/field/password-toggle')
                    ],
                    'field-title' => [
                        'text' => 'Password'
                    ]
                ]) ?>

                <div class="text --margin-top-large --text-small">
                    <div>
                        By clicking Create Account, you agree to our
                        <a class='link link--color link--primary --inline --text-small' href="<?= $app['route']->uri('legal.terms-of-service') ?>" target="_blank"><b>Terms</b></a>
                        and
                        <a class='link link--color link--primary --inline --text-small' href="<?= $app['route']->uri('legal.privacy-policy') ?>" target="_blank"><b>Privacy Policy</b></a>
                    </div>
                </div>

                <button class="button button--large button--primary --margin-top-large --width-full">Create New Account</button>
            </form>
        </div>
    </div>
</div>
