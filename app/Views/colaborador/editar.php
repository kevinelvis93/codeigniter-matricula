<?= $header; ?>

    <div class="container mt-5">
        <h2>Editar Colaborador</h2>
        <form action="<?= site_url('colaborador/actualizar/' . $colaborador['id_colaborador']) ?>" method="post">
            <div class="form-group">
                <label for="nombres">Nombres</label>
                <input type="text" class="form-control" id="nombres" name="nombres" value="<?= esc($colaborador['nombres']) ?>" required>
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?= esc($colaborador['apellidos']) ?>" required>
            </div>
            <div class="form-group">
                <label for="dni">DNI</label>
                <input type="text" class="form-control" id="dni" name="dni" value="<?= esc($colaborador['dni']) ?>" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo" value="<?= esc($colaborador['correo']) ?>" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?= esc($colaborador['telefono']) ?>" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="<?= esc($colaborador['direccion']) ?>" required>
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select class="form-control" id="estado" name="estado" required>
                    <option value="1" <?= $colaborador['estado'] == 1 ? 'selected' : '' ?>>Activo</option>
                    <option value="0" <?= $colaborador['estado'] == 0 ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="<?= site_url('colaborador') ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>


<?= $footer; ?>
