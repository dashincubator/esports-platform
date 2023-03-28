<div class="modal modal--small" data-modifier='black' id='modal-match-accept'>
    <div class="modal-content --background-grey">

        <form action="<?= $app['route']->uri('ladder.match.accept.command', $data['slugs']->toArray()) ?>" data-submit='processing' method="post">

            <input id='modal-match-accept-id' name="id" type="hidden">
            <input name="team" type="hidden" value="<?= $data['team.id'] ?>">

            <div class="modal-section">
                <div class="page-header">
                    <h3 class="page-header-title">
                        Accepting
                        <span class="--text-red" id='modal-match-accept-gametype'></span>
                        Match
                    </h3>
                    <span class="page-header-subtitle --text-crop-bottom">
                        Select <b class='--text-black'><span id='modal-match-accept-playersPerTeam'></span> member(s)</b> that will participate in this match
                    </span>
                </div>
            </div>

            <div class="modal-section --border-grey-500 --border-small --border-top">
                <div class="table">
                    <div class="table-header --background-black-500 --text-white">
                        <span class="table-item --button-badge-width">&nbsp;</span>
                        <span class="table-item table-item--padding-left table-item--width-fill">Member</span>
                        <span class="table-item table-item--padding-left table-item--width-small --flex-center">Playing</span>
                    </div>

                    <?php foreach ($data['roster'] as $member): ?>
                        <?php $eligible = $member['eligible']; ?>

                        <div class="table-row --background-grey-300">
                            <div class='table-item --button-badge-width --flex-center'>
                                <div class="button button--<?= $eligible ? 'green' : 'red' ?> button--circle button--badge tooltip" data-hover="tooltip">
                                    <div class="icon icon--badge">
                                        <?= $app['svg']($eligible ? 'check' : 'close') ?>
                                    </div>

                                    <span class="tooltip-content tooltip-content--message tooltip-content--e">
                                        <?= $eligible ? 'Eligible' : 'Ineligible' ?>
                                    </span>
                                </div>
                            </div>

                            <div class="table-item table-item--padding-left table-item--width-fill">
                                <div class="text --flex-vertical --image-large-left --image-large-height">
                                    <img class="image image--large --absolute-vertical-left" src="<?= $app['upload']->path('user.avatar', $member['user.avatar']) ?>">

                                    <div class="text">
                                        <a href="<?= $app['route']->uri('profile', ['slug' => $member['user.slug']]) ?>" class="link link--color link--primary link--text --inline --text-bold --text-truncate">
                                            <?= $member['user.username'] ?>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-item table-item--padding-left table-item--width-small --flex-center">
                                <div class="field field--primary" data-change='field-checkbox'>
                                    <label class="field-check field-check--checkmark field-check--checkmark-size">
                                        <span class="field-mask">
                                            <input class="field-tag" name='roster[]' type="checkbox" data-ref='trigger:change' value="<?= $member['user.id'] ?>" <?= !$eligible ? '--disabled' : '' ?>>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="text --margin-top-large --text-small">
                    <div>
                        By clicking create match you agree to follow the
                        <a class='link link--color link--primary --inline --text-bold --text-small' href="<?= $app['route']->uri('legal.terms-of-service') ?>" target="_blank">
                            <?= ucfirst($data['ladder.type']) ?> Rules
                        </a>
                        for this match.
                    </div>
                </div>

                <div class="--flex-horizontal-space-between --width-full">
                    <div class="button button--black button--large --margin-top-large --width-half" data-click='modal' data-modal='match-accept'>
                        Cancel
                    </div>

                    <button class="button button--large button--primary --margin-top-large --width-half">
                        Accept Match
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>
