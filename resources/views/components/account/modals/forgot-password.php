<div class="modal modal--small" data-modifier='black' id='modal-forgot-password'>
    <div class="modal-content --background-grey">

        <div class="modal-section">
            <div class="page-header --text-center">
                <h2 class="page-header-title">Recover Your Account</h2>
            </div>

            <form action="<?= $app['route']->uri('account.auth.forgot-password.command') ?>" data-submit='processing' method="post">
                <?= $include('@components/field/input/default', [
                    'field' => [
                        'class' => 'field--secondary field--white --margin-top-large --width-full'
                    ],
                    'field-description' => [
                        'text' => "We'll send a reset link to the email you provide."
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'name' => 'email',
                            'required' => true
                        ]
                    ],
                    'field-title' => [
                        'text' => 'Email'
                    ]
                ]) ?>

                <button class="button button--large button--secondary --margin-top-large --width-full">Recover Account</button>
            </form>
        </div>

    </div>
</div>
