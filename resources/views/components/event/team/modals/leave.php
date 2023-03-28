<div class="modal modal--small" data-modifier='black' id='modal-team-leave'>
    <div class="modal-content --background-grey">

        <div class="modal-section">
            <div class="page-header">
                <h3 class="page-header-title">
                    Leaving Team
                </h3>
                <span class="page-header-subtitle">
                    Are you sure you want to leave <b class='--text-black'><?= $data['team.name'] ?></b>?
                </span>
            </div>

            <form action="<?= $data['action'] ?>" class='--margin-top-large' data-submit='processing' method="post">
                <button class="button button--large button--primary --width-full">Leave Team</button>
            </form>
        </div>

    </div>
</div>
