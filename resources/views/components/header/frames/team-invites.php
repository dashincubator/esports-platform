<div class="frame" id="frame-header-invites">
    <div class="header-user-menu-header">
        <?= $include('components/close') ?>

        <b class="text --text-large --text-white">
            Team Invites
        </b>
    </div>

    <div class="link-menu link-menu--padding --background-grey-300">
        <?php $invites = $app['auth']->getTeamInvites(); ?>

        <?php if (!count($invites['ladder'])): ?>
            <div class="link link--button-menu --cursor-default --flex-center --width-full">
                0 team invites found
            </div>
        <?php endif; ?>

        <?php foreach ($invites as $key => $group): ?>
            <?php foreach ($group as $team): ?>
                <?php
                    $event = $app[$key]->get($team[$key]);
                    $game = $app['game']->get($event['game']);
                    $slugs = [
                        'game' => $game['slug'],
                        'platform' => $app['platform']->get($game['platform'])['slug'],
                        'team' => $team['slug'],
                        "{$key}" => $event['slug']
                    ];
                ?>

                <a class="link link--button-menu link--button-white link--text --width-full" href="<?= $app['route']->uri("{$key}.team", $slugs) ?>">
                    <div class="header-user-menu-invite-wrapper --flex-vertical --image-large-height --image-large-left --width-full">
                        <img class="image image--large --absolute-vertical-left" src="<?= $app['upload']->path("{$key}.team.avatar", $team['avatar']) ?>">

                        <div class="text-list">
                            <div class="text">
                                <b class="--text-truncate">
                                    <?= $team['name'] ?>
                                </b>
                            </div>

                            <div class='text --text-small'>
                                <span class="--text-truncate">
                                    <?= $event['name'] ?>
                                </span>
                            </div>
                        </div>

                        <form action="<?= $app['route']->uri("{$key}.team.invite.respond.command", $slugs) ?>" class='header-user-menu-invite-buttons --flex-horizontal' method="post">
                            <button class="header-user-menu-invite-button button button--circle button--green button--small tooltip" data-hover="tooltip" name="accept" value="1">
                                <div class="icon icon--small">
                                    <?= $app['svg']('check') ?>
                                </div>

                                <span class="tooltip-content tooltip-content--message tooltip-content--w">Accept</span>
                            </button>

                            <button class="header-user-menu-invite-button button button--circle button--primary button--small tooltip" data-hover="tooltip" name="decline" value="1">
                                <div class="icon icon--small">
                                    <?= $app['svg']('close') ?>
                                </div>

                                <span class="tooltip-content tooltip-content--message tooltip-content--w">Decline</span>
                            </button>
                        </form>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
</div>
