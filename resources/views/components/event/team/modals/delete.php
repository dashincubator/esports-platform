<div class="modal modal--small" data-modifier='black' id='modal-team-delete'>
    <div class="modal-content --background-grey">

        <div class="modal-section">
            <div class="page-header">
                <h3 class="page-header-title">
                    Deleting Team
                </h3>
                <span class="page-header-subtitle">
                    Are you sure you want to delete <b class='--text-black'><?= $data['team.name'] ?></b>?
                </span>
            </div>
        </div>

        <div class="modal-section --border-grey-500 --border-small --border-top">
            <p>
                If you delete this team it will be <b class='--text-black'>gone forever</b>.
                Are you sure you want to proceed?
            </p>

            <form action="<?= $data['action'] ?>" class='--margin-top-large' data-submit='processing' method="post">
                <button class="button button--large button--primary --width-full">Delete Team</button>
            </form>
        </div>

    </div>
</div>
