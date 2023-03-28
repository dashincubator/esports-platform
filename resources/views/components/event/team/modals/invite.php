<div class="modal modal--small" data-modifier='black' id='modal-team-invite'>
    <div class="modal-content --background-grey">

        <div class="modal-section">
            <div class="page-header">
                <h3 class="page-header-title">
                    Invite User To Team
                </h3>
                <span class="page-header-subtitle">
                    Send team invites using Game ID or <?= ucfirst($app['config']->get('app.name')) ?> ID.
                </span>

                <div class="page-header-subtitle">
                    <div class="list">
                        <div class="list-item list-item--bulletpoint --icon-right">
                            <?= ucfirst($app['config']->get('app.name')) ?> ID can be found on the user profile beside this icon

                            <div class="icon --absolute-vertical-right --text-black">
                                <?= $app['svg']('id') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-section --border-grey-500 --border-small --border-top">
            <form action="<?= $data['action'] ?>" data-submit='processing' method="post">
                <?= $include('@components/field/select/white', [
                    'field' => [
                        'class' => 'field--primary --width-full'
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'name' => 'column',
                            'required' => true
                        ]
                    ],
                    'field-title' => [
                        'text' => 'Invite By'
                    ],
                    'options' => $data['findBy']
                ]) ?>

                <?= $include('@components/field/input/default', [
                    'field' => [
                        'class' => 'field--primary field--white --margin-top-small --width-full'
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'name' => 'value',
                            'required' => true
                        ]
                    ]
                ]) ?>

                <button class="button button--large button--primary --margin-top-large --width-full">Send Invite</button>
            </form>
        </div>

    </div>
</div>
