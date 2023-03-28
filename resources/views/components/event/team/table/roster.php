<div class="table table--margin-top">
    <div class="table-header --background-black-500 --text-white">
        <div class="table-item --button-badge-width"></div>
        <span class="table-item table-item--padding-left table-item--width-fill">Member</span>

        <?php if (!$data['hide.matches']): ?>
            <div class="table-item table-item--width-large tooltip --hidden-800px --text-center" data-hover='tooltip'>
                Arena Rank
                <span class="tooltip-content tooltip-content--message tooltip-content--n">
                    <?= $data['platform.name'] ?> <?= $data['game.name'] ?> Rank
                </span>
            </div>
        <?php endif; ?>


        <span class="table-item <?= $data['hide.matches'] ? 'table-item--padding-left' : '' ?> table-item--width-larger --hidden-800px --text-center">
            <?= $app['config']->get('game.accounts.' . $data['game.account']) ?>
        </span>

        <?php if ($data['hide.matches']): ?>
            <div class="table-item table-item--width-small tooltip --hidden-800px --text-center" data-hover='tooltip'>
                Score
                <span class="tooltip-content tooltip-content--message tooltip-content--n">
                    Player Score
                </span>
            </div>
        <?php else: ?>
            <div class="table-item table-item--width-small tooltip --hidden-1000px --text-center" data-hover='tooltip'>
                W%
                <span class='tooltip-content tooltip-content--message tooltip-content--n'>
                    Average Win Percentage
                </span>
            </div>
        <?php endif; ?>

        <?php if ($data['editor']): ?>
            <div class="table-item table-item--padding-left team-profile-table-arrow">
                <div class="icon"></div>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($data['editor']): ?>
        <form action="<?= $app['route']->uri("{$data['key']}.team.update.roster.command", $data['slugs']->toArray()) ?>" data-submit='processing' method="post">
    <?php endif; ?>

    <?php foreach ($data['roster'] as $member): ?>
        <?php
            $eligible = $member['eligible'];
            $self = $member['user.id'] === $app['auth']->getId();
            $table = $app['roster']->toTableItemArray($member['rank']);
        ?>

        <div class="table-row table-row--border<?= $eligible ? '-green' : '-red' ?> <?= $data['editor'] ? '' : 'table-row--more-right-800px' ?> --background-grey-300 --margin-top-border-small" <?= $data['editor'] ? "data-click='accordion' data-accordion='roster-{$member['user.id']}'" : '' ?>>
            <div class="table-item --button-badge-width">
                <div class="button button--badge button--<?= $eligible ? 'green' : 'red' ?> button--circle tooltip --absolute-center" data-hover="tooltip">
                    <div class="icon icon--badge">
                        <?= $app['svg']($eligible ? 'check' : 'close') ?>
                    </div>
                    <span class="tooltip-content tooltip-content--message tooltip-content--e">
                        <?= $eligible ? 'Eligible' : 'Ineligible' ?>
                    </span>
                </div>
            </div>
            <div class="table-item table-item--padding-left table-item--width-fill --button-right-hidden-800px --button-small-right">
                <div class="text --flex-vertical --image-large-left --image-large-height --image-left-hidden-600px">
                    <img class="image image--large --absolute-vertical-left --hidden-600px" src="<?= $app['upload']->path('user.avatar', $member['user.avatar']) ?>">

                    <a class="link link--color link--primary --inline --text-bold --text-truncate " href="<?= $app['route']->uri('profile', ['slug' => $member['user.slug']]) ?>">
                        <?= $member['user.username'] ?>
                    </a>
                </div>

                <?php if ($member['user.isMembershipActive']): ?>
                    <div class="membership-icon tooltip --hidden-800px --absolute-vertical-right" data-hover="tooltip">
                        <img src="/images/membership.svg" class="membership-icon-image">
                        <span class="tooltip-content tooltip-content--message tooltip-content--w">Premium Member</span>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!$data['hide.matches']): ?>
                <span class="table-item table-item--width-large --flex-center --hidden-800px"><?= $table['rank'] ?></span>
            <?php endif; ?>

            <div class="table-item <?= $data['hide.matches'] ? 'table-item--padding-left' : '' ?> table-item--width-larger tooltip --hidden-800px" data-click='copy' data-copy='account-<?= $member['user.id'] ?>' data-hover='tooltip'>
                <div class="team-profile-table-account --button-small-right --flex-vertical">
                    <div class="text">
                        <span class="--text-small --text-truncate">
                            <?= $member['account'] ? $member['account'] : '-' ?>
                        </span>
                    </div>

                    <div class="team-profile-table-account-copy --absolute-vertical-right --flex-center">
                        <div class="icon --text-black">
                            <?= $app['svg']('copy') ?>
                        </div>
                    </div>
                </div>

                <span class="tooltip-content tooltip-content--message tooltip-content--ne --cursor-pointer">
                    Copy
                </span>
            </div>

            <input class='copy' id="copy-account-<?= $member['user.id'] ?>" type="text" value='<?= $member['account'] ? $member['account'] : '-' ?>'>

            <?php if ($data['hide.matches']): ?>
                <span class="table-item table-item--width-small --flex-center --hidden-800px"><?= $member['score'] ?></span>
            <?php else: ?>
                <span class="table-item table-item--width-small --flex-center --hidden-1000px"><?= $table['wp'] ?>%</span>
            <?php endif; ?>

            <?php if ($data['editor']): ?>
                <div class="table-item table-item--padding-left team-profile-table-arrow --flex-center">
                    <div class="accordion-arrow icon --text-black">
                        <?= $app['svg']('arrow-head') ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="button button--clear button--large button--more button--white tooltip --absolute-vertical-right --border-color-override --border-grey --border-left --visible-800px" data-hover="tooltip">
                    <div class="icon">
                        <?= $app['svg']('menu/more') ?>
                    </div>

                    <div class="tooltip-content tooltip-content--ne tooltip-content--table --background-black-500 --cursor-default">
                        <div class="scroller">
                            <div class='scroller-content' data-ref='scroller' data-wheel='scroller'>
                                <div class="table table--tooltip">
                                    <div class='table-header --background-black-500 --text-white'>
                                        <?php if (!$data['hide.matches']): ?>
                                            <div class='table-item table-item--width-large tooltip --text-center' data-hover='tooltip'>
                                                Arena Rating
                                                <span class='tooltip-content tooltip-content--message tooltip-content--c'>
                                                    <?= $data['platform.name'] ?> <?= $data['game.name'] ?> Rank
                                                </span>
                                            </div>
                                        <?php endif; ?>

                                        <span class='table-item table-item--width-larger --text-center'><?= $app['config']->get('game.accounts.' . $data['game.account']) ?></span>

                                        <?php if (!$data['hide.matches']): ?>

                                        <?php endif; ?>

                                        <?php if ($data['hide.matches']): ?>
                                            <div class='table-item table-item--width-small tooltip --text-center' data-hover='tooltip'>
                                                Score
                                                <span class='tooltip-content tooltip-content--message tooltip-content--c'>
                                                    Player Score
                                                </span>
                                            </div>
                                        <?php else: ?>
                                            <div class='table-item table-item--width-small tooltip --text-center' data-hover='tooltip'>
                                                W%
                                                <span class='tooltip-content tooltip-content--message tooltip-content--c'>
                                                    Average Win Percentage
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="table-row --background-white --margin-top-border-small">
                                        <?php if (!$data['hide.matches']): ?>
                                            <span class="table-item table-item--width-large --flex-center"><?= $table['rating'] ?></span>
                                        <?php endif; ?>

                                        <div class="table-item table-item--width-larger tooltip --image-large-height" data-click='copy' data-copy='account-<?= $member['user.id'] ?>' data-hover='tooltip'>
                                            <div class="team-profile-table-account --button-small-right --flex-vertical">
                                                <div class="text --text-black --text-small --text-truncate --width-full">
                                                    <?= $member['account'] ? $member['account'] : '-' ?>
                                                </div>

                                                <div class="team-profile-table-account-copy --absolute-vertical-right --flex-center">
                                                    <div class="icon --text-black">
                                                        <?= $app['svg']('copy') ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <span class="tooltip-content tooltip-content--message tooltip-content--ne --cursor-pointer">
                                                Copy
                                            </span>
                                        </div>

                                        <?php if ($data['hide.matches']): ?>
                                            <span class="table-item table-item--width-small --flex-center"><?= $member['score'] ?></span>
                                        <?php else: ?>
                                            <span class="table-item table-item--width-small --flex-center"><?= $table['wp'] ?>%</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($data['editor']): ?>
            <div class="accordion --border-dashed --border-small <?= $self ? '--not-allowed' : '' ?>" id='accordion-roster-<?= $member['user.id'] ?>'>
                <?php if ($self): ?>
                    <div class="--disabled --width-full">
                <?php endif; ?>

                <label class="button button--large button--basic button--transparent field field--primary --width-full" data-change='field-checkbox'>
                    <div class="field-check field-check--checkmark field-check--right">
                        <b class="field-title">Kick member</b>
                        <span class="field-description">
                            Remove member from this team
                        </span>

                        <span class="field-mask field-mask--switch">
                            <input class="field-tag" type="checkbox" name='kick[]' data-ref='trigger:change' value="<?= $member['user.id'] ?>">
                        </span>
                    </div>
                </label>

                <div class="border border--dashed border--small"></div>

                <input type="hidden" name="permissions[<?= $member['user.id'] ?>][managesMatches]" value="0">

                <label class="button button--large button--basic button--transparent field field--primary --width-full" data-change='field-checkbox'>
                    <div class="field-check field-check--right field-check--switch">
                        <b class="field-title">Modify matches</b>
                        <span class="field-description">
                            Member will be able to report or correct scores for any match played by this team
                        </span>

                        <span class="field-mask">
                            <input class="field-tag" name='permissions[<?= $member['user.id'] ?>][managesMatches]' type="checkbox" data-ref='trigger:change' value="1" <?= $member['managesMatches'] ? 'checked' : '' ?>>
                        </span>
                    </div>
                </label>

                <div class="border border--dashed border--small"></div>

                <input type="hidden" name="permissions[<?= $member['user.id'] ?>][managesTeam]" value="0">

                <label class="button button--large button--basic button--transparent field field--primary --width-full" data-change='field-checkbox'>
                    <div class="field-check field-check--right field-check--switch">
                        <b class="field-title">Modify team</b>
                        <span class="field-description">
                            Member will be able to edit team profile, roster, and delete this team.
                        </span>

                        <span class="field-mask">
                            <input class="field-tag" name='permissions[<?= $member['user.id'] ?>][managesTeam]' type="checkbox" data-ref='trigger:change' value="1" <?= $member['managesTeam'] ? 'checked' : '' ?>>
                        </span>
                    </div>
                </label>

                <?php if ($self): ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<?php if ($data['editor']): ?>
        <section class="section section--margin-top-small --flex-end">
            <button class="button button--large button--primary button--width --width-half-800px">Update Roster</button>
        </section>
    </form>
<?php endif; ?>
