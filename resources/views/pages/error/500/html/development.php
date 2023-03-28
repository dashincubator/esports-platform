<!DOCTYPE html>
<html>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, height=device-height, initial-scale=1, minimum-scale=1, minimal-ui'>

        <title>Something Broke</title>

        <link href='/css/app.css' id='stylesheet' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700' rel='stylesheet'>

        <noscript>
            Javascript Is Required To View This Website! <br />

            <a href='https://www.enable-javascript.com/' target='_blank'>
                Follow These Instructions To Enable Javascript In Your Web Browser.
            </a>
        </noscript>
    </head>
    <body>
        <section class='site' data-ref='scrollbar' data-scroll='scrollbar,scrolltop' data-scrollbar='site' data-scrolltop='site' id='site'>
            <section class="page">
                <?php if (count($data['errors'])): ?>
                    <div class="container container--width-1000px">
                        <?php foreach ($data['errors'] as $index => $error): ?>
                            <section class="section section--margin-top<?= ($index === 0) ? '-large' : '' ?> --border-dashed --border-small --border-top">
                                <div class="page-header">
                                    <div class="page-header-category text-group">
                                        <b class="text --text-small --text-primary --text-uppercase"><?= $error['class'] ?></b>
                                        <span class="text --text-small"><?= date("F j, h:i A T", $error['time']) ?></span>
                                    </div>
                                    <h2 class="page-header-title"><?= $error['message'] ?></h2>
                                    <div class="page-header-subtitle">
                                        thrown from <b class='--text-black'>...<?= array_reverse(explode('public_html', $error['file'], 2))[0]; ?></b> at line <b class='--text-black'><?= $error['line']; ?></b>
                                    </div>
                                </div>

                                <div class="table table--margin-top">
                                    <div class="table-header --background-black-500 --text-white" data-click='accordion' data-accordion='<?= $index ?>'>
                                        <div class="table-item --button-width"></div>
                                        <span class="table-item table-item--padding-left table-item--width-fill">
                                            File
                                        </span>
                                        <span class="table-item table-item--width-small --text-center">
                                            Line
                                        </span>
                                    </div>

                                    <?php
                                        $trace = $error['trace'] ?? [];

                                        if (is_object($error['trace'])) {
                                            $trace = $trace->toArray();
                                        }
                                        
                                        $trace[] = [
                                            'file' => '{main}',
                                            'line' => ''
                                        ];
                                        $trace = array_values($trace);

                                        $count = count($trace) - 1;
                                    ?>

                                    <div class="accordion accordion--noanimation" id="accordion-<?= $index ?>">
                                        <?php foreach ($trace as $index => $info): ?>
                                            <div class="table-row table-row--border-gradient-black --background-grey-300 --margin-top-border">
                                                <div class="table-item --button-height --button-width">
                                                    <b class="button button--black button--border button--circle button--static --absolute-vertical-left">
                                                        <span class="text --text-small --absolute-center --center-text"><?= $count--; ?></span>
                                                        &nbsp;
                                                    </b>
                                                </div>

                                                <div class="table-item table-item--padding-left table-item--width-fill --flex-vertical">
                                                    <?= array_reverse(explode('public_html', $info['file'], 2))[0]; ?>
                                                </div>
                                                <div class="table-item table-item--width-small --flex-center">
                                                    <?= $info['line'] ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </section>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="page-header --absolute-center">
                        <div class="container --flex-center --text-center">
                            <h1 class="page-header-title">
                                <span class="--text-primary">error.log</span> is empty
                            </h1>
                            <span class="page-header-subtitle">
                                If you fixed all of the site errors congrats!
                            </span>

                            <a class="button button--large button--primary button--width --margin-top-large" href='/'>
                                Back To Homepage
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </section>

            <div class="scrollbar scrollbar--fixed" id='scrollbar-site'></div>
        </section>

        <script src='/js/app.js'></script>
    </body>
</html>
