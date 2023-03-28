<div class="modal modal--small" data-modifier='black' id='modal-lock-team'>
    <div class="modal-content --background-grey">

        <div class="modal-section">
            <div class="page-header">
                <h3 class="page-header-title">
                    Locking Team
                </h3>
                <span class="page-header-subtitle">
                    Are you sure you want to lock your team?
                </span>
            </div>
        </div>

        <div class="modal-section --border-grey-500 --border-small --border-top">
            <p>
                In order to compete in this event you must lock your team.
                Once this is done you will not be able to delete this team, or edit the roster. 
            </p>

            <form action="<?= $data['action'] ?>" class='--margin-top-large' data-submit='processing' method="post">
                <button class="button button--large button--primary --width-full">Lock Team</button>
            </form>
        </div>

    </div>
</div>
