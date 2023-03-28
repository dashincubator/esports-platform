<div class="modal modal--small" data-modifier='black' id='modal-update-password'>
    <div class="modal-content --background-grey">

        <div class="modal-section">
            <div class="page-header --text-center">
                <h2 class="page-header-title">Update Password</h2>
            </div>

            <form action="<?= $app['route']->uri('account.update.password.command') ?>" data-submit='processing' method="post">
                <?= $include('@components/field/input/default', [
                    'field' => [
                        'class' => 'field--secondary field--white --margin-top-large --width-full'
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'name' => 'current',
                            'required' => true,
                            'type' => 'password'
                        ]
                    ],
                    'field-text' => [
                        'html' => $include('@components/field/password-toggle')
                    ],
                    'field-title' => [
                        'text' => 'Current Password'
                    ]
                ]) ?>

                <?= $include('@components/field/input/default', [
                    'field' => [
                        'class' => 'field--secondary field--white --margin-top --width-full'
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'name' => 'new',
                            'required' => true,
                            'type' => 'password'
                        ]
                    ],
                    'field-text' => [
                        'html' => $include('@components/field/password-toggle')
                    ],
                    'field-title' => [
                        'text' => 'New Password'
                    ]
                ]) ?>

                <button class="button button--large button--secondary --margin-top-large --width-full">Update Password</button>
            </form>
        </div>

    </div>
</div>
