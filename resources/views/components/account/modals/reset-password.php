<div class="modal modal--small" data-modifier='black' id='modal-reset-password'>
    <div class="modal-content --background-grey">

        <div class="modal-section">
            <div class="page-header --text-center">
                <h2 class="page-header-title">Reset Password</h2>
            </div>

            <form action="<?= $app['route']->uri('account.auth.reset-password.command') ?>" data-submit='processing' method="post">
                <?= $include('@components/field/input/default', [
                    'field' => [
                        'class' => 'field--secondary field--white --margin-top-large --width-full'
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
                        'text' => 'New Password'
                    ]
                ]) ?>

                <input name="code" value="<?= $data['code'] ?>" type="hidden">
                <input name="id" value="<?= $data['id'] ?>" type="hidden">

                <button class="button button--large button--secondary --margin-top-large --width-full">Submit</button>
            </form>
        </div>

        <span data-click="modal" data-modal="reset-password" data-ref="trigger:click"></span>

    </div>
</div>
