<?php $layout('@layouts/master/default') ?>

<div class='header-spacer'></div>

<div class='container --max-width-600px'>
    <section class='section section--margin-top-large'>
        <div class='page-header --text-center'>
            <h1 class='page-header-title'>
                Manage Organizations
            </h1>
        </div>
    </section>

    <section class='section section--margin-top-large'>
        <?= $include('@components/link/scroller/border-grey', [
            'links' => [
                [
                    'active' => true,
                    'frame' => 'organizations',
                    'text' => 'Organizations'
                ],
                [
                    'href' => $app['route']->uri('admincp.organization.create'),
                    'text' => 'Create Organization'
                ]
            ],
            'scroller-content-wrapper' => [
                'class' => 'scroller-content-wrapper--center'
            ]
        ]) ?>

        <section class="section section--margin-top">
            <div class="table">
                <div class="table-header table-header--more-right --background-black-500 --text-white">
                    <span class="table-item table-item--width-fill">
                        Active Organizations
                    </span>
                </div>

                <?php foreach ($data['organizations'] as $organization): ?>
                    <div class="table-row table-row--more-right --background-grey-300 --margin-top-border">
                        <div class="table-item table-item--width-fill">
                            <div class="text-list">
                                <b class="text">
                                    <?= $organization['name'] ?>
                                </b>
                                <div class="text --text-small">
                                    <span class="--text-truncate">
                                        <?= $organization['domain'] ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <a
                            class="button button--clear button--large button--more button--white tooltip --absolute-vertical-right --border-color-override --border-grey --border-left"
                            data-hover="tooltip"
                            href='<?= $app['route']->uri('admincp.organization.edit', ['id' => $organization['id']]) ?>'
                        >
                            <div class="icon">
                                <?= $app['svg']('settings') ?>
                            </div>

                            <span class="tooltip-content tooltip-content--message tooltip-content--w">Edit Organization</span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </section>
</div>
