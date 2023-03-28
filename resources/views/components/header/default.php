<?php $modifiers = $app['header']->getModifiers() ?>

<header class="header header--<?= $modifiers['header'] ?>" id='scrollto-top'>
    <div class="container --flex-horizontal-space-between">

        <div class='--flex'>
            <a class="header-logo" href='<?= $app['route']->uri('index') ?>'>
                <img class="header-logo-image" src="<?= $modifiers['children'] === 'white' ? '/images/logomark.svg' : '/images/logo.svg' ?>">
            </a>
        </div>

        <div class='--flex'>
            <div class="header-item --flex-vertical">
                <div class="link link--<?= $modifiers['children'] ?> tooltip" data-hover="tooltip" data-click="modal" data-modal="games">
                    <div class="icon">
                        <?= $app['svg']('menu/grid') ?>
                    </div>
                    <span class="tooltip-content tooltip-content--message tooltip-content--s">
                        Browse Events
                    </span>
                </div>
            </div>

            <?php if ($app['auth']->isGuest()): ?>
                <span class="header-item link link--<?= $modifiers['children'] ?>" data-click="modal" data-modal="sign" <?= $app['route']->has('account.auth.sign-') ? "data-ref='trigger:click'" : "" ?>>
                    Sign In
                </span>
            <?php else: ?>
                <div class="header-user tooltip" data-click="frame,tooltip" data-frame='header-<?= $app['route']->has('admincp') ? 'admin' : 'menu' ?>'>
                    <div class="header-user-avatar header-user-avatar--<?= $modifiers['children'] ?>">
                        <img class="header-user-avatar-image image image--large" src="<?= $app['upload']->path('user.avatar', $app['auth']->getAvatar()) ?>">

                        <?php if ($app['auth']->isMembershipActive()): ?>
                            <div class="header-user-avatar-membership membership-icon membership-icon--small">
                                <img src="/images/membership.svg" class="membership-icon-image">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="header-user-menu tooltip-content tooltip-content--menu tooltip-content--se" data-stopclick>
                        <div class="header-user-menu-wrapper tooltip-content-wrapper" data-ref='scrollbar' data-scroll='scrollbar' data-scrollbar='user-menu'>
                            <div class="header-user-menu-header header-user-menu-header--top"></div>

                            <?php if ($app['auth']->isAdmin() && $app['auth']->getOrganization() === $app['organization']->getId()): ?>
                                <?= $include('frames/admin') ?>
                            <?php endif; ?>

                            <?php foreach(['bank', 'menu', 'membership', 'team-invites', 'teams'] as $frame): ?>
                                <?= $include("frames/{$frame}") ?>
                            <?php endforeach; ?>
                        </div>

                        <div class='scrollbar' id='scrollbar-user-menu'></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</header>
