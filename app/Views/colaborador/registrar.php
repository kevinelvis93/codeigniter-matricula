<?= $header; ?>

<div class="container mt-4">
    <h2>Registrar Nuevo Colaborador</h2>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('/colaborador/registrar') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="id_tipo_identificacion" class="form-label">Tipo de Identificación</label>
            <select class="form-select" id="id_tipo_identificacion" name="id_tipo_identificacion" onchange="mostrarNumeroDocumento()" required>
                <option value=""> -- Seleccionar -- </option>
                <?php if (!empty($tipoIdentificacion)): ?>
                    <?php foreach ($tipoIdentificacion as $identificacion): ?>
                        <option 
                            value="<?= esc($identificacion['id_tipo_identificacion']) ?>" 
                            <?= old('id_tipo_identificacion') == $identificacion['id_tipo_identificacion'] ? 'selected' : '' ?>>
                            <?= esc($identificacion['identificacion_nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

        </div>

        <div class="mb-3" id="campo-numero-documento" style="display: none;">
            <label for="identificacion_descripcion" class="form-label">Número de Documento</label>
            <input 
                type="text" 
                class="form-control" 
                id="identificacion_descripcion" 
                name="identificacion_descripcion" 
                value="<?= old('identificacion_descripcion') ?>">
        </div>


        <div class="mb-3">
            <label for="nombres" class="form-label">Nombres</label>
            <input type="text" class="form-control texto-mayuscula" id="nombres" name="nombres" value="<?= old('nombres') ?>" required>
        </div>

        <div class="mb-3">
            <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
            <input type="text" class="form-control texto-mayuscula" id="apellido_paterno" name="apellido_paterno" value="<?= old('apellido_paterno') ?>" required>
        </div>

        <div class="mb-3">
            <label for="apellido_materno" class="form-label">Apellido Materno</label>
            <input type="text" class="form-control texto-mayuscula" id="apellido_materno" name="apellido_materno" value="<?= old('apellido_materno') ?>" required>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="departamento" class="form-label">Departamento</label>
                <select id="departamento" name="departamento" class="form-control">
                    <option value="">Seleccione un departamento</option>
                    <?php if (!empty($departamentos) && is_array($departamentos)): ?>
                        <?php foreach ($departamentos as $departamento): ?>
                            <option value="<?= esc($departamento['codigo_departamento']) ?>">
                                <?= esc($departamento['nombre_departamento']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No hay departamentos disponibles</option>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Provincia -->
            <div class="col-md-4 mb-3">
                <label for="provincia" class="form-label">Provincia</label>
                <select class="form-control" id="provincia" name="provincia">
                    <option value="">Seleccione una provincia</option>
                </select>
            </div>

            <!-- Distrito -->
            <div class="col-md-4 mb-3">
                <label for="distrito" class="form-label">Distrito</label>
                <select class="form-control" id="distrito" name="distrito">
                    <option value="">Seleccione un distrito</option>
                </select>
            </div>
        </div>



        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="<?= old('direccion') ?>">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <div class="input-group">
                <input 
                    type="password" 
                    class="form-control" 
                    id="password" 
                    name="password" 
                    value="<?= old('password') ?>" 
                    required>
                <button type="button" class="btn btn-secondary" id="generate-password" onclick="generarPassword()">Generar</button>
                <button type="button" class="btn btn-secondary" id="toggle-password" onclick="togglePassword()">Mostrar</button>
            </div>
        </div>



        <!-- Correos dinámicos -->
        <div class="mb-3">
            <label for="emails" class="form-label">Correos Electrónicos</label>
            <div id="emails-container" onload="manejarCorreos()">
                <?php 
                $emails = old('emails') ?? [''];
                foreach ($emails as $email): ?>
                    <div class="input-group mb-2">
                        <input type="email" class="form-control" name="emails[]" value="<?= esc($email) ?>" placeholder="Correo">
                        <button type="button" class="btn btn-success add-email">+</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Teléfonos dinámicos -->
        <div class="mb-3">
            <label for="telefonos" class="form-label">Teléfonos</label>
            <div id="telefonos-container" onload="manejarTelefonos()">
                <?php 
                $telefonos = old('telefonos') ?? [''];
                foreach ($telefonos as $telefono): ?>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" name="telefonos[]" value="<?= esc($telefono) ?>" placeholder="Teléfono">
                        <button type="button" class="btn btn-success add-phone">+</button>
                    </div>
                <?php endforeach; ?>
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
                                <?= in_array($rol['id'], old('roles', [])) ? 'checked' : '' ?>>
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
            <div id="roles-error" class="text-danger" style="display: none;">Debes seleccionar al menos un rol.</div>
        </div>




        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>

<?= $footer; ?>
