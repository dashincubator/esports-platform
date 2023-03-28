<div class="<?= $data['description'] ? '--margin-top' : '' ?> --width-full">
    <?php if ($data['description']): ?>
        <p><?= $data['description'] ?></p>
    <?php endif; ?>

    <div class="card --border-dashed --border-small --margin-top --width-full">
        <?php $permissions = $data['position.permissions'] ? $data['position.permissions']->toArray() : []; ?>

        <?php foreach ($data['permissions'] as $permission): ?>
            <?= $include('checkbox', array_merge($permission->toArray(), [
                'checked' => in_array($permission['value'], $permissions),
                'value' => $permission['value']
            ])) ?>
        <?php endforeach; ?>
    </div>
</div>
