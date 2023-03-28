<div class="frame" id="frame-header-admin">
    <div class="header-user-menu-header">
        <?= $include('components/close') ?>

        <b class="text --text-large --text-white">
            Admin Panel
        </b>
    </div>

    <?php if ($app['auth']->can('owner')): ?>
        <?= $include('components/links', ['links' => [
            [
                'frame' => 'header-dashboard',
                'svg' => 'dashboard',
                'text' => 'Dashboard'
            ]
        ]]) ?>

        <div class="border border--grey border--small"></div>
    <?php endif; ?>

    <?= $include('components/links', ['links' => [
        ($app['auth']->can('manageGames') ? [
            'frame' => 'header-games',
            'svg' => 'disc',
            'text' => 'Games'
        ] : []),
        ($app['auth']->can('manageGames') ? [
            'frame' => 'header-platforms',
            'svg' => 'joystick',
            'text' => 'Platforms'
        ] : [])
    ]]) ?>

    <div class="border border--grey border--small"></div>

    <?php if ($app['auth']->can('manageLadders')): ?>
        <?= $include('components/links', ['links' => [
            ($app['auth']->can('manageLadders') ? [
                'frame' => 'header-ladders',
                'svg' => 'ladder',
                'text' => 'Ladders'
            ] : [])
        ]]) ?>

        <div class="border border--grey border--small"></div>
    <?php endif; ?>

    <?= $include('components/links', ['links' => [
        (($app['auth']->can('manageAdmin') || $app['auth']->can('manageAdminPositions')) ? [
            'route' => [
                'name' => 'admincp.admin.manage'
            ],
            'svg' => 'team',
            'text' => 'Manage Admin' . (!$app['auth']->can('manageAdmin') ? ' Positions' : '')
        ] : []),
        (($app['auth']->can('manageOrganizations')) ? [
            'route' => [
                'name' => 'admincp.organization.manage'
            ],
            'svg' => 'web',
            'text' => 'Manage Organizations'
        ] : []),
        (false && $app['auth']->can('manageBankWithdraws') ? [
            'route' => [
                'name' => 'admincp.bank.withdraws'
            ],
            'svg' => 'bank-withdraw',
            'text' => 'Process Bank Withdraws'
        ] : [])
    ]]) ?>
</div>

<?php if ($app['auth']->can('owner')): ?>
    <?= $include('admin/dashboard') ?>
<?php endif; ?>

<?php if ($app['auth']->can('manageGames')): ?>
    <?= $include('admin/games') ?>
    <?= $include('admin/platforms') ?>
<?php endif; ?>

<?php if ($app['auth']->can('manageLadders') || $app['auth']->can('manageLadderGametypes')): ?>
    <?= $include('admin/ladders') ?>
<?php endif; ?>
