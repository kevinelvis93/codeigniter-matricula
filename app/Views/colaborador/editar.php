<?= $header; ?>

<div class="container mt-4">
    <h2>Editar Colaborador</h2>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('/colaborador/actualizar/' . $colaborador['id']) ?>" method="post">
        <?= csrf_field() ?>

        <?php
    $oldTipoIdentificacion = session()->getFlashdata('input')['id_tipo_identificacion'] ?? $colaborador['id_tipo_identificacion'] ?? '';
    $oldIdentificacionDescripcion = session()->getFlashdata('input')['identificacion_descripcion'] ?? $colaborador['identificacion_descripcion'] ?? '';
?>

<div class="mb-3">
    <label for="id_tipo_identificacion" class="form-label">Tipo de Identificación</label>
    <select class="form-select" id="id_tipo_identificacion" name="id_tipo_identificacion" onchange="mostrarNumeroDocumento()" required>
        <option value=""> -- Seleccionar -- </option>
        <?php if (!empty($tipoIdentificacion)): ?>
            <?php foreach ($tipoIdentificacion as $identificacion): ?>
                <option 
                    value="<?= esc($identificacion['id_tipo_identificacion']) ?>" 
                    <?= ($oldTipoIdentificacion == $identificacion['id_tipo_identificacion']) ? 'selected' : '' ?>>
                    <?= esc($identificacion['identificacion_nombre']) ?>
                </option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>

<div class="mb-3" id="campo-numero-documento">
    <label for="identificacion_descripcion" class="form-label">Número de Documento</label>
    <input 
        type="text" 
        class="form-control" 
        id="identificacion_descripcion" 
        name="identificacion_descripcion" 
        value="<?= esc($oldIdentificacionDescripcion) ?>">
</div>


        <div class="mb-3">
            <label for="nombres" class="form-label">Nombres</label>
            <input type="text" class="form-control texto-mayuscula" id="nombres" name="nombres" value="<?= esc($colaborador['nombres'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
            <input type="text" class="form-control texto-mayuscula" id="apellido_paterno" name="apellido_paterno" value="<?= esc($colaborador['apellido_paterno'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="apellido_materno" class="form-label">Apellido Materno</label>
            <input type="text" class="form-control texto-mayuscula" id="apellido_materno" name="apellido_materno" value="<?= esc($colaborador['apellido_materno'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="<?= esc($colaborador['direccion'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <div class="input-group">
                <input 
                    type="password" 
                    class="form-control" 
                    id="password" 
                    name="password">
                <button type="button" class="btn btn-secondary" id="toggle-password" onclick="togglePassword()">Mostrar</button>
            </div>
            <small class="form-text text-muted">Deja en blanco si no deseas cambiar la contraseña.</small>
        </div>

   <!-- Correos dinámicos -->
<div class="mb-3">
    <label for="emails" class="form-label">Correos Electrónicos</label>
    <div id="emails-container">
        <?php foreach ($colaborador['emails'] as $email): ?>
            <div class="input-group mb-2">
                <input type="email" class="form-control" name="emails[]" value="<?= esc($email) ?>" placeholder="Correo">
                <button type="button" class="btn btn-danger remove-email">-</button>
            </div>
        <?php endforeach; ?>
        <button type="button" class="btn btn-success add-email">+ Agregar</button>
    </div>
</div>

<!-- Teléfonos dinámicos -->
<div class="mb-3">
    <label for="telefonos" class="form-label">Teléfonos</label>
    <div id="telefonos-container">
        <?php foreach ($colaborador['telefonos'] as $telefono): ?>
            <div class="input-group mb-2">
                <input type="text" class="form-control" name="telefonos[]" value="<?= esc($telefono) ?>" placeholder="Teléfono">
                <button type="button" class="btn btn-danger remove-phone">-</button>
            </div>
        <?php endforeach; ?>
        <button type="button" class="btn btn-success add-phone">+ Agregar</button>

    </div>
</div>

        <div class="mb-3">
            <label for="roles" class="form-label">Roles</label>
            <div id="roles">
                <?php if (!empty($roles)): ?>
                    <?php foreach ($roles as $rol): ?>
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                id="rol-<?= esc($rol['id']) ?>" 
                                name="roles[]" 
                                value="<?= esc($rol['id']) ?>"
                                <?= in_array($rol['id'], $colaborador['roles'] ?? []) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="rol-<?= esc($rol['id']) ?>">
                                <?= esc($rol['nombre']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">No hay roles disponibles.</p>
                <?php endif; ?>
            </div>
            <small class="form-text text-muted">Selecciona al menos un rol marcando una casilla.</small>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="<?= base_url('/colaborador') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?= $footer; ?>
