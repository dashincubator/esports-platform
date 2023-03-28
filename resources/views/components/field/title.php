<div class='field-title <?= $data['class'] ?>'>
    <?= $data['!html'] ?> <?= $data['text'] ?>

    <?php if ($data['required']): ?>
        <div class="field-required tooltip" data-hover='tooltip'>
            <span class="tooltip-content tooltip-content--message tooltip-content--ne">Required</span>
        </div>
    <?php endif; ?>
</div>
