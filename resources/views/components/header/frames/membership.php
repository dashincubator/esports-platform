<div class="frame" id="frame-header-membership">
    <div class="header-user-menu-header">
        <?= $include('components/close') ?>

        <div class="text-list --flex-center">
            <?php if ($app['auth']->isMembershipActive()): ?>
                <span class="text --flex-center --text-grey --text-small">Membership Expires</span>
                <b class="text --flex-center --text-white">
                    <?= $app['time']->toMembershipFormat($app['auth']->getMembershipExpiresAt()) ?>
                </b>
            <?php else: ?>
                <span class="text --text-white">No Active Membership</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="link-menu link-menu--padding --background-grey-300">
        <b class="link-title link-title--button-menu">Purchase Membership</b>

        <form action="<?= $app['route']->uri('account.membership.purchase.command') ?>" method="post">
            <?php foreach ($app['config']->get('membership.options') as $option): ?>
                <button class="link link--button-menu link--button-white link--text --width-full" name='days' value="<?= $option['days'] ?>">
                    <div class="text-list --icon-left --width-full">
                        <div class="icon --absolute-vertical-left">
                            <?= $app['svg']($option['svg']) ?>
                        </div>

                        <div class="text --flex-horizontal-space-between">
                            <b><?= $option['text'] ?></b>
                            <b class='--text-black'>$<?= number_format($option['price'], 0) ?></b>
                        </div>
                        <div class="text --text-small">
                            <span class="--text-truncate">
                                <?= $option['days'] ?> Days
                            </span>
                        </div>
                    </div>
                </button>
            <?php endforeach; ?>
        </form>
    </div>
</div>
