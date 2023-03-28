<div class="frame" id="frame-header-dashboard">
    <div class="header-user-menu-header">
        <?= $include('components/close') ?>

        <b class="text --text-large --text-white">
            Dashboard
        </b>
    </div>

    <div class="border border--grey border--small"></div>

    <div class='link-menu link-menu--padding --background-grey-300'>
        <?php foreach ($app['auth']->getSiteCounters() as $title => $value): ?>
            <div class='link link--button-menu --cursor-default --width-full'>
                <div class="text-list">
                    <b class="text">
                        <?= $title ?>
                    </b>
                    <span class="text --text-black">
                        <?= $value ?>
                    </span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>
