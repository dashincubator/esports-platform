<div class='alert alert--<?= $data['key'] ?> anchor anchor--ne <?= count($data['messages']) ? '--active' : '' ?>' id='alert-<?= $data['key'] ?>'>
    <div class='alert-close alert-close--<?= $data['modifier'] ?> --flex-center' data-click='deactivate' data-deactivate='alert-<?= $data['key'] ?>'>
        <div class='icon icon--small --text-white'>
            <?php $app['svg']('close'); ?>
        </div>
    </div>

    <div class='alert-messages' id='alert-<?= $data['key'] ?>-messages'>
        <?php foreach ($data['messages'] as $message): ?>
            <p><?= $message ?></p>
        <?php endforeach; ?>
    </div>
</div>
