<div class="modal modal--small" data-modifier='black' id='modal-match-report'>
    <div class="modal-content --background-grey">

        <div class="modal-section">
            <div class="page-header">
                <h3 class="page-header-title">
                    Submitting Match Scores
                </h3>
                <span class="page-header-subtitle">
                    Disputing on purpose will result in a penalty for your entire team.
                    <a class='link link--color link--primary --inline' href="#">
                        <b>How bad can the penalty be?</b>
                    </a>
                </span>
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
                            'name' => 'placement',
                            'required' => true
                        ]
                    ],
                    'field-title' => [
                        'text' => 'My team placed'
                    ],
                    'options' => $data['options'],
                    'selected' => $data['selected']
                ]) ?>

                <button class="button button--large button--primary --margin-top-large --width-full">Submit Results</button>
            </form>
        </div>
    </div>
</div>
