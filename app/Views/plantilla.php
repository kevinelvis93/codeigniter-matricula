<?= $header; ?>

<?php $session = session(); ?>

<p>Hola soy la plantilla</p>
<p>ID de Usuario: <?= esc($session->get('nombre')) ?></p>

<?php $roles = $session->get('roles') ?? []; ?>
<?php if (!empty($roles)): ?>
    <p>Roles: <?= implode(', ', $roles) ?></p>
<?php else: ?>
    <p>No tienes roles asignados.</p>
<?php endif; ?>

<?= $footer; ?>
