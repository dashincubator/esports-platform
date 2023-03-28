<div class="modal modal--small" data-modifier='black' id='modal-team-create'>
    <div class="modal-content --background-grey">

        <div class="modal-section">
            <div class="page-header">
                <h3 class="page-header-title">
                    <?= $data['title'] ?>
                </h3>
            </div>
        </div>

        <div class="modal-section --border-grey-500 --border-small --border-top">
            <form action="<?= $data['action'] ?>" data-submit='processing' method="post">
                <?= $include('@components/field/input/default', [
                    'field' => [
                        'class' => 'field--primary field--white --width-full'
                    ],
                    'field-description' => [
                        'text' => '3–32 characters'
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'name' => 'name',
                            'required' => true
                        ]
                    ],
                    'field-title' => [
                        'text' => 'Team Name'
                    ]
                ]) ?>

                <button class="button button--large button--primary --margin-top-large --width-full">Create Team</button>
            </form>
        </div>
    </div>
</div>
