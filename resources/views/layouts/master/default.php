<?php
    $cachebust = $app['config']->get('app.debug') ? '?time=' . time() : '';

    // Easier Than Flushing Cloudflare + NGINX Cache For Primary Site Resources
    $version = !$cachebust ? '?v25' : '';
?>

<!DOCTYPE html>
<html lang='en'>
    <head>
        <?php // Preserve Order, These Tags Need To Be Listed First ?>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <meta name='viewport' content='width=device-width, height=device-height, initial-scale=1, maximum-scale=1, minimal-ui'>

        <title><?= $app['seo']->title() ?></title>
        <meta name='description' content='<?= $app['seo']->description() ?>'>

        <?php
            // Application Details

            /*
            <meta name='apple-mobile-web-app-title' content=''>
            <meta name='application-name' content=''>
            <meta name='keywords' content=''>
            */

            // Twitter Card

            /*
            <meta name='twitter:card' content='summary_large_image'>
            <meta name='twitter:site' content='@'>
            <meta name='twitter:creator' content='@'>

            <meta name='twitter:title' content=''>
            <meta name='twitter:description' content=''>
            <meta name='twitter:image' content=''>
            <meta name='twitter:url' content=''>
            */
        ?>

        <?php // Control Search Engine Behavior ?>
        <meta name='robots' content='index,follow'>
        <meta name='googlebot' content='index,follow'>

        <?php // iOS Add To Homescreen Capable ( Web App ) ?>
        <meta name='apple-mobile-web-app-capable' content='yes'>
        <meta name='apple-mobile-web-app-status-bar-style' content='black-translucent'>

        <?php // Android Add To Homescreen ( Web App ) ?>
        <meta name='mobile-web-app-capable' content='yes'>
        <meta name='theme-color' content='#e0ebf7'>

        <?php // Favicons ?>
        <link rel='icon' href='/icons/favicon/192.png' sizes='192x192' type='image/png'>
        <link rel='icon' href='/icons/favicon/96.png' sizes='96x96' type='image/png'>
        <link rel='icon' href='/icons/favicon/32.png' sizes='32x32' type='image/png'>
        <link rel='icon' href='/icons/favicon/16.png' sizes='16x16' type='image/png'>

        <?php // PWA Web App Manifest ?>
        <link rel='manifest' href='/manifest.webmanifest'>

        <?php // CSS ?>
        <?php if ($cachebust): ?>
            <link href='/css/dev.css<?= $cachebust . $version ?>' rel='stylesheet' type='text/css'>
        <?php endif; ?>

        <link href='/css/app.css<?= $cachebust . $version ?>' id='stylesheet' rel='stylesheet' type='text/css'>

        <?php // NoJS ?>
        <noscript>
            Javascript Is Required To View This Website! <br />

            <a href='https://www.enable-javascript.com/' target='_blank'>
                Follow These Instructions To Enable Javascript In Your Web Browser.
            </a>
        </noscript>
    </head>
    <body>
        <section class='site' data-ref='scrollbar,scrollbar:dispatch' data-scroll='scrollbar,scrolltop' data-scrollbar='site' data-scrolltop='site' id='site'>
            <!-- <div class="page-header --absolute-center">
                <div class="container --flex-center --text-center">
                    <h1 class="page-header-title">GAMRS Is Temporarily Offline</h1>
                    <span class="page-header-subtitle">
                        We are in the process of rebranding, be back soon...
                    </span>
                </div>
            </div> -->

            <?= $include('@components/header/default') ?>

            <section class='page' id='page'>
                <?= $section('content') ?>
            </section>

            <?= $include('@components/footer/default') ?>
        </section>

        <section class="overlays">
            <?php foreach (['drawers', 'modals'] as $overlay): ?>
                <div class="<?= $overlay ?>" id="<?= $overlay ?>" data-click="<?= $overlay ?>,tooltip-detoggle" data-ref='scrollbar' data-scroll='scrollbar' data-scrollbar='site'>
                    <?php foreach ($app[trim($overlay, 's')]->toArray() as $template => $data): ?>
                        <?= $include($template, array_merge($data->toArray(), [
                            'html' => $data['!html']
                        ])) ?>
                    <?php endforeach; ?>

                    <div class="<?= $overlay ?>-close">
                        <div class="icon icon--small">
                            <?= $app['svg']('close') ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="anchors">
                <?php foreach ($app['anchor']->toArray() as $anchor): ?>
                    <?= $include($anchor) ?>
                <?php endforeach; ?>
            </div>
        </section>

        <div class="scrollbar scrollbar--fixed" id='scrollbar-site'></div>

        <?php if ($cachebust): ?>
            <script src='/js/dev.js<?= $cachebust . $version ?>'></script>
        <?php endif; ?>

        <script src='/js/app.js<?= $cachebust . $version ?>'></script>
    </body>
</html>
