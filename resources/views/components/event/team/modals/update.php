<div class="modal modal--small" data-modifier='black' id='modal-team-update'>
    <div class="modal-content --background-grey">

        <div class="modal-section">
            <div class="page-header">
                <h3 class="page-header-title">
                    Update Team Profile
                </h3>
            </div>
        </div>

        <div class="modal-section --border-grey-500 --border-small --border-top">
            <form action="<?= $data['action'] ?>" data-submit='processing' method="post">
                <?= $include('@components/field/textarea/default', [
                    'field' => [
                        'class' => 'field--primary field--white --width-full'
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'name' => 'bio',
                            'value' => $data['bio']
                        ],
                        'directives' => [
                            'keydown' => 'field-autoresize'
                        ],
                    ],
                    'field-title' => [
                        'text' => 'Short Bio'
                    ]
                ]) ?>

                <button class="button button--large button--primary --margin-top-large --width-full">Save Changes</button>
            </form>
        </div>

    </div>
</div>
