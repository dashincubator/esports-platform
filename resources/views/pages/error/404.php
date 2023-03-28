<?php $layout('@layouts/master/default') ?>

<div class="page-header --absolute-center">
    <div class="container --flex-center --text-center">
        <h1 class="page-header-title">404 Page Not Found</h1>
        <span class="page-header-subtitle">
            The page you're looking for does not exist...
        </span>

        <a class="button button--large button--primary button--width --margin-top-large" href='<?= $app['route']->uri('index') ?>'>
            Back To Homepage
        </a>
    </div>
</div>
