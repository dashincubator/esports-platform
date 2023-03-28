<?php
    $editor = $app['auth']->managesTeam($data['team.id']);
    $onTeam = $app['auth']->onTeam($data['team.id']);

    $links = [];
    $links[] = [
        'active' => true,
        'text' => 'Team Profile'
    ];
?>

<div class="header-spacer header-spacer--full"></div>

<div class="container">
    <div class="profile-banner" id='upload-banner' style="background-image: url(<?= $app['upload']->path($data['key'] . '.team.banner', $data['team.banner']) ?>)">
        <?php if ($editor): ?>
            <form action="<?= $app['route']->uri("{$data['key']}.team.update.profile.command", $data['slugs']->toArray()) ?>" class='profile-avatar-form' data-submit='processing' enctype="multipart/form-data" method="post">
                <?= $include('@components/field/upload/overlay', [
                    'field-tag' => [
                        'attributes' => [
                            'name' => 'banner'
                        ]
                    ],
                    'onchange' => true,
                    'tooltip-content' => [
                        'text' => 'Upload Banner'
                    ]
                ]) ?>
            </form>
        <?php endif; ?>

        <div class="profile-avatar" id='upload-avatar' style="background-image: url(<?= $app['upload']->path("{$data['key']}.team.avatar", $data['team.avatar']) ?>)">
            <?php if ($editor): ?>
                <form action="<?= $app['route']->uri("{$data['key']}.team.update.profile.command", $data['slugs']->toArray()) ?>" class='profile-avatar-form' data-submit='processing' enctype="multipart/form-data" method="post">
                    <?= $include('@components/field/upload/overlay', [
                        'field-tag' => [
                            'attributes' => [
                                'name' => 'avatar'
                            ]
                        ],
                        'onchange' => true,
                        'tooltip-content' => [
                            'text' => 'Upload Avatar'
                        ]
                    ]) ?>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class='profile-container'>
        <section class='profile-header'>
            <section class='profile-header-section --button-small-right --button-small-left-800px --text-center-800px'>
                <h1 class="--text-crop-both">
                    <?= $data['team.name'] ?>
                </h1>

                <?php if ($onTeam): ?>
                    <?php if ($editor): ?>
                        <?php
                            $app['modal']->add('@components/event/team/modals/delete', [
                                'action' => $app['route']->uri("{$data['key']}.team.delete.command", $data['slugs']->toArray())
                            ]);

                            $app['modal']->add('@components/event/team/modals/invite', [
                                'action' => $app['route']->uri("{$data['key']}.team.invite.create.command", $data['slugs']->toArray())
                            ]);

                            $app['modal']->add('@components/event/team/modals/update', [
                                'action' => $app['route']->uri("{$data['key']}.team.update.profile.command", $data['slugs']->toArray()),
                                'bio' => $data['team.bio']
                            ]);

                            $links[] = [
                                'modal' => 'team-delete',
                                'text' => 'Delete Team'
                            ];

                            $links[] = [
                                'modal' => 'team-update',
                                'text' => 'Edit Team'
                            ];

                            $links[] = [
                                'modal' => 'team-invite',
                                'text' => 'Invite User'
                            ];
                        ?>
                    <?php else: ?>
                        <?php
                            $app['modal']->add('@components/event/team/modals/leave', [
                                'action' => $app['route']->uri("{$data['key']}.team.leave.command", $data['slugs']->toArray())
                            ]);

                            $links[] = [
                                'modal' => 'team-leave',
                                'text' => 'Leave Team'
                            ];
                        ?>
                    <?php endif; ?>
                <?php endif; ?>
            </section>

            <section class="profile-header-section profile-header-section--description">
                <p class="--button-small-right --button-small-left-800px --text-center-800px --text-crop-both">
                    <?= $data['platform.name'] ?> <?= $data['game.name'] ?>
                    <a href='<?= $app['route']->uri($data['key'], $data['slugs']->toArray()) ?>' class='link link--color link--primary --text-black --inline'>
                        <b><?= $data["{$data['key']}.name"] ?></b>
                    </a>
                    Team
                </p>
            </section>

            <?php if ($data['team.bio']): ?>
                <section class="profile-header-section profile-header-section--bio">
                    <p class='--text-center-800px --text-crop-both'><?= $data['team.bio'] ?></p>
                </section>
            <?php endif; ?>

            <section class="profile-header-section">
                <div class="group --flex-horizontal-800px --margin-top">
                    <div class="group-item --icon-left --margin-top">
                        <div class="icon --absolute-vertical-left">
                            <?= $app['svg']('calendar') ?>
                        </div>

                        <span class="text">Created&nbsp;</span>
                        <b class='text'><?= $app['time']->toCreatedFormat($data['team.createdAt']) ?></b>
                    </div>
                    <div class="group-item --icon-left --margin-top">
                        <div class="icon --absolute-vertical-left">
                            <?= $app['svg']('user-circle') ?>
                        </div>
                        <div class="text">
                            <span class="text">Founded By&nbsp;</span>
                            <b class='text'>
                                <a href="<?= $app['route']->uri('profile', ['slug' => $data['founder.slug']]) ?>" class="link link--color link--primary --text-black --inline">
                                    <?= $data['founder.username'] ?>
                                </a>
                            </b>
                        </div>
                    </div>
                    <div class="group-item --icon-left --margin-top">
                        <div class="icon --absolute-vertical-left">
                            <?= $app['svg']('id') ?>
                        </div>
                        <b class="text"><?= $data['team.id'] ?></b>
                    </div>
                </div>
            </section>

            <?php if ($data['header.buttons.html']): ?>
                <section class="profile-header-section">
                    <div class="button-group --width-full-800px --margin-top">
                        <?= $data['!header.buttons.html'] ?>
                    </div>
                </section>
            <?php endif; ?>

            <section class='profile-header-nav'>
                <?= $include('@components/link/scroller/border-grey', [
                    'links' => $links,
                    'scroller-content-wrapper' => [
                        'class' => '--flex-horizontal-800px'
                    ]
                ]) ?>
            </section>
        </section>

        <div class="columns">
            <div class="column column--padding-right column--width-fill column--width-full-1200px">
                <section class='section section--margin-top'>
                    <div class='page-header'>
                        <h2 class='page-header-title --text-crop-top'>Record</h2>
                    </div>

                    <div class='table table--margin-top'>
                        <?php $prefix = '@components/leaderboard/table' . ($data['ladder'] && !$data['ladder.isMatchFinderRequired'] ? '/score' : ''); ?>

                        <?= $include("{$prefix}/header", [
                            'fill' => [
                                'text' => $data['record.fill.text']
                            ]
                        ]) ?>
                        <?= $include("{$prefix}/row", [
                            'fill' => [
                                'html' => $data['!record.fill.html']
                            ],
                            'table' => $data['record.table']
                        ]) ?>
                    </div>
                </section>

                <?= $data['!warzone.html'] ?>

                <section class="section section--margin-top">
                    <div class="page-header">
                        <h2 class="page-header-title">Roster</h2>
                    </div>

                    <?= $include('table/roster', compact('editor')) ?>
                </section>
            </div>

            <div class="column column--padding-left column--width-fixed column--width-full-1200px">
                <?php if (!$data['hide.matches']): ?>
                    <section class='section section--margin-top'>
                        <div class='page-header'>
                            <h2 class='page-header-title --text-crop-top'>Match History</h2>
                        </div>

                        <div class='table table--margin-top'>
                            <div class="table-header --background-black-500 --border-radius --text-white">
                                <span class="table-item table-item--width-fill">Details</span>
                            </div>

                            <div class='team-profile-matches'>
                                <div data-ref='scrollbar,scrollbar:dispatch' data-scroll='scrollbar' data-scrollbar='team-matches'>
                                    <?php if (!count($data['matches'])): ?>
                                        <?= $include('@components/table/row/empty', [
                                            'class' => '--button-small-height',
                                            'text' => 'No matches found'
                                        ]) ?>
                                    <?php endif; ?>

                                    <?php foreach($data['matches'] as $match): ?>
                                        <div class="table-row --background-grey-300 --margin-top-border">
                                            <div class="table-item table-item--padding-right table-item--width-fill --button-small-height --flex-vertical --icon-small-left">
                                                <a class='link--color link--primary tooltip --absolute-vertical-left' data-hover='tooltip' href='<?= $match['href'] ?>'>
                                                    <div class="icon icon--small">
                                                        <?= $app['svg']('match') ?>
                                                    </div>

                                                    <span class="tooltip-content tooltip-content--e tooltip-content--message">
                                                        View Match Details
                                                    </span>
                                                </a>

                                                <?= $match['!fill.html'] ?>
                                            </div>

                                            <div class="table-item team-profile-table-result --button-small-height --flex-center">
                                                <span class='--text-bolder <?= $match['result.class'] ?>'>
                                                    <?= ($match['result.text'] === 'Active Match') ? '-' : $match['result.text'][0] ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="scrollbar" id='scrollbar-team-matches'></div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if ($data['important.html']): ?>
                    <section class="section <?= $data['hide.matches'] ? 'section--margin-top' : 'section--margin-top-small' ?>">
                        <?= $include('@components/list/important', [
                            'html' => $data['!important.html']
                        ]) ?>
                    </section>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
