<footer class='footer'>
    <div class='footer-mask'>
        <?= $app['svg']('footer-mask') ?>
    </div>

    <div class='footer-content'>
        <div class='container --flex-center'>
            <div>
                <img class='footer-esportsplus' src='/images/esportsplus/logo.svg'>
            </div>

            <div class='footer-links'>
                <div class='group --flex-horizontal --margin-top-small'>
                    <a class='group-item link link--grey --margin-top-small <?= $app['route']->activate('faq') ?>' href='<?= $app['route']->uri('faq') ?>'>
                        FAQ
                    </a>
                    <a class='group-item link link--grey --margin-top-small <?= $app['route']->activate('legal.privacy-policy') ?>' href='<?= $app['route']->uri('legal.privacy-policy') ?>'>
                        Privacy Policy
                    </a>
                    <a class='group-item link link--grey --margin-top-small <?= $app['route']->activate('legal.terms-of-service') ?>' href='<?= $app['route']->uri('legal.terms-of-service') ?>'>
                        Terms Of Service
                    </a>
                </div>
            </div>

            <span class='footer-copyright --text-small --text-small-crop'>
                &copy; <?= date('Y') ?> GAMRS All Rights Reserved
            </span>
        </div>
    </div>

    <img class='footer-foreground footer-foreground--left' src='/images/footer/foreground-left.svg'>
    <img class='footer-foreground footer-foreground--right' src='/images/footer/foreground-right.svg'>
    <img class='footer-background' src='/images/footer/background.svg'>
</footer>
