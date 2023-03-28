<div class="frame --active" id='frame-header-menu'>
    <div class="header-user-menu-header">
        <div class="header-user-menu-header-button header-user-menu-header-button--left button button--black button--circle button--ghost tooltip" data-click="modal,tooltip-detoggle" data-hover="tooltip" data-modal="bank-withdraw">
            <div class="icon">
                <?= $app['svg']('minus') ?>
            </div>

            <span class="tooltip-content tooltip-content--message tooltip-content--e">
                Withdraw
            </span>
        </div>

        <div class="header-user-menu-header-button header-user-menu-header-button--right button button--circle button--primary tooltip" data-click="modal,tooltip-detoggle" data-hover="tooltip" data-modal="bank-deposit">
            <div class="icon">
                <?= $app['svg']('plus') ?>
            </div>

            <span class="tooltip-content tooltip-content--message tooltip-content--w">
                Deposit
            </span>
        </div>

        <div class="text-list --flex-center">
            <span class="text --flex-center --text-grey --text-small">Bank Balance</span>
            <b class="text --flex-center --text-large --text-white">$<?= number_format($app['auth']->getBankBalance(), 2) ?></b>
        </div>

        <div id='header-user-menu-reset' data-click='frame' data-frame='header-menu'></div>
    </div>

    <?= $include('components/links', ['links' => [
        ($app['auth']->isAdmin() && $app['auth']->getOrganization() === $app['organization']->getId() ? [
            'frame' => 'header-admin',
            'svg' => 'dashboard',
            'text' => 'Admin Panel'
        ] : []),
        [
            'frame' => 'header-bank',
            'svg' => 'dollar-circle',
            'text' => 'Bank Activity'
        ],
        [
            'route' => [
                'name' => 'account.edit',
            ],
            'svg' => 'settings',
            'text' => 'Edit Account'
        ],
        [
            'frame' => 'header-membership',
            'svg' => 'membership',
            'text' => 'My Membership'
        ],
        [
            'route' => [
                'name' => 'profile',
                'variables' => ['slug' => $app['auth']->getSlug()]
            ],
            'svg' => 'user-circle',
            'text' => 'My Profile'
        ]
    ]]) ?>

    <div class="border border--grey border--small"></div>

    <?= $include('components/links', ['links' => [
        [
            'frame' => 'header-teams',
            'svg' => 'team',
            'text' => 'My Teams'
        ],
        [
            'frame' => 'header-invites',
            'svg' => 'team-add',
            'text' => 'Team Invites'
        ]
    ]]) ?>

    <div class="border border--grey border--small"></div>

    <?= $include('components/links', ['links' => [
        [
            'route' => [
                'name' => 'account.auth.sign-out.command'
            ],
            'svg' => 'logout',
            'text' => 'Sign Out'
        ]
    ]]) ?>
</div>
