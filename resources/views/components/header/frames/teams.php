<div class="frame" id="frame-header-teams">
    <div class="header-user-menu-header">
        <?= $include('components/close') ?>

        <b class="text --text-large --text-white">
            My Teams
        </b>
    </div>

    <div class="link-menu link-menu--padding --background-grey-300">
        <?php $teams = $app['auth']->getTeams(); ?>

        <?php if (!count($teams['ladder'])): ?>
            <div class="link link--button-menu --cursor-default --flex-center --width-full">
                0 teams found
            </div>
        <?php endif; ?>

        <?php foreach ($teams as $key => $group): ?>
            <?php foreach ($group as $team): ?>
                <?php
                    if (!$app[$key]->has($team[$key])) {
                        continue;
                    }

                    $event = $app[$key]->get($team[$key]);
                    $game = $app['game']->get($event['game']);
                    $platform = $app['platform']->get($game['platform']);
                    $slugs = [
                        'game' => $game['slug'],
                        'platform' => $platform['slug'],
                        'team' => $team['slug'],
                        "{$key}" => $event['slug']
                    ];
                ?>

                <a class="link link--button-menu link--button-white link--text --width-full" href="<?= $app['route']->uri("{$key}.team", $slugs) ?>">
                    <div class="--game-icons-small-right --flex-vertical --image-large-height --image-large-left --width-full">
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

                        <div class="game-icons --absolute-vertical-right">
                            <div class="button button--circle button--<?= $platform['view'] ?> button--small button--static">
                                <div class="icon">
                                    <?= $app['svg']('platform/' . $platform['view']) ?>
                                </div>
                            </div>

                            <div class="game-icon-game button--circle button button--<?= $game['view'] ?> button--small button--static">
                                <div class="icon">
                                    <?= $app['svg']('game/' . $game['view']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
</div>
