<div class="frame" id='match-support'>
    <section class="section section--margin-top">
        <p class='--text-bold --text-uppercase'>
            Please be aware of the following <span class="--text-primary">before submitting</span> tickets:
        </p>

        <div class="list">
            <div class="list-item list-item--bulletpoint">
                <span>
                    If you are experiencing an issue that does not involve this match please use the
                    general support center
                </span>
            </div>
            <span class="list-item list-item--bulletpoint">
                Provide as many details as possible to effectively communicate what happened during the match.
            </span>
            <span class="list-item list-item--bulletpoint">
                Include picture/video evidence that clearly displays scores and Game Id's.
            </span>
            <span class="list-item list-item--bulletpoint">
                All youtube video proof must be set to public. Private videos cannot be viewed by support staff.
            </span>
        </div>

        <form action="<?= $data['action'] ?>" class='section section--margin-top' data-submit='processing' method="post">
            <?php $section->start('container') ?>
                <div class="--width-full">
                    <div class="button-group" id='{id}'>
                    </div>
                </div>
            <?php $section->end() ?>

            <?php $section->start('template') ?>
                <div class="button-group-item button button--black button--large tooltip --margin-top-small" data-click='remove' data-hover='tooltip'>
                    <div class="--icon-small-right">
                        {value}
                        <div class="icon icon--small --absolute-vertical-right">
                            <?= $app['svg']('close') ?>
                        </div>
                    </div>

                    <input name="{name}" type="hidden" value="{value}">

                    <span class="tooltip-content tooltip-content--message tooltip-content--nw">Remove</span>
                </div>
            <?php $section->end() ?>

            <div class='tickets-add-links --max-width-400px --width-full'>
                <div class="tickets-add-links-center card link link--color link--primary --absolute-full --border-dashed --border-default --border-small --flex-center" data-click='activate' data-activate='ticket-add-links' id='ticket-add-links'>
                    <div class="text --icon-small-left">
                        <div class="icon --absolute-vertical-left">
                            <?= $app['svg']('plus-circle') ?>
                        </div>

                        <b>Add Links</b>
                    </div>
                </div>

                <?= $include('@components/field/input/default', [
                    'field' => [
                        'class' => 'field--border field--primary --width-full'
                    ],
                    'field-description' => [
                        'text' => 'Press enter to add link'
                    ],
                    'field-tag' => [
                        'attributes' => [
                            'placeholder' => 'Add Link'
                        ],
                        'directives' => [
                            'keydown' => 'field-tags',
                            'field-tags-container' => 'links',
                            'field-tags-template' => $app['html']->escape(str_replace('{name}', 'links[]', $section('template')))
                        ]
                    ],
                    'field-title' => [
                        'text' => 'Links'
                    ]
                ]) ?>

                <?= str_replace('{id}', 'links', $section('container')) ?>
            </div>

            <?= $include('@components/field/textarea/default', [
                'field' => [
                    'class' => 'field--border field--primary --margin-top-large --width-full'
                ],
                'field-tag' => [
                    'attributes' => [
                        'name' => 'content',
                        'required' => true
                    ],
                    'directives' => [
                        'keydown' => 'field-autoresize'
                    ],
                ],
                'field-title' => [
                    'text' => 'Provide a Detailed Summary of the Issue'
                ]
            ]) ?>

            <div class="--flex-end --margin-top-large --width-full">
                <button class="button button--large button--primary button--width --width-full-400px">Create Ticket</button>
            </div>
        </form>
    </section>
</div>
