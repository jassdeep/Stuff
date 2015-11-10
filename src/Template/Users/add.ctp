<div class="users form">
<?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Add User') ?></legend>
        <?= $this->Form->input('username') ?>
        <?= $this->Form->input('password') ?>
        <?= $this->Form->input('role', [
            'options' => ['admin' => 'Admin', 'customer' => 'Customer']
        ]) ?>
   </fieldset>
<?= $this->Form->button(__('Create user')); ?>
<?= $this->Form->end() ?>
</div>