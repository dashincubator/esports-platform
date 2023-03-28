<div class="modal modal--small" data-modifier='black' id='modal-delete'>
    <div class="modal-content --background-grey">

        <div class="modal-section">
            <div class="page-header">
                <h3 class="page-header-title">
                    Deleting <?= ucwords($data['key']) ?>
                </h3>
                <span class="page-header-subtitle">
                    Are you sure you want to delete <b class='--text-black'><?= $data['value'] ?></b>?
                </span>
            </div>
        </div>

        <div class="modal-section --border-grey-500 --border-small --border-top">
            <p>
                If you delete this <?= strtolower($data['key']) ?> it will be <b class='--text-black'>gone forever</b>.
                Are you sure you want to proceed?
            </p>

            <form action="<?= $data['action'] ?>" class='--margin-top-large' data-submit='processing' method="post">
                <button class="button button--large button--primary --width-full">Delete <?= ucwords($data['key']) ?></button>
            </form>
        </div>

    </div>
</div>
