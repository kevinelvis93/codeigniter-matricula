<?= $header; ?>

<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="text-primary">Lista de Colaboradores</h2>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="<?= base_url('/colaborador/registrar/') ?>" class="btn btn-success">
                <i class="fas fa-plus"></i> Registrar
            </a>
        </div>
    </div>

    <!-- Mostrar mensajes de éxito o error -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Formulario de búsqueda -->
    <form method="get" action="<?= base_url('/colaborador') ?>" class="row g-3 mb-3">
        <div class="col-md-9">
            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, usuario, DNI, correo, etc." value="<?= esc($search) ?>">
        </div>
        <div class="col-md-3 text-md-end">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search"></i> Buscar
            </button>
        </div>
    </form>

    <!-- Tabla de resultados -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <?php 
                        $fields = [
                            'ID' => 'id',
                            'Roles' => 'roles',
                            'Usuario' => 'usuario',
                            'Nombres' => 'nombres',
                            'Apellidos' => 'apellidos',
                            'DNI' => 'dni',
                            'Dirección' => 'direccion',
                            'Correos' => 'emails',
                            'Teléfonos' => 'telefonos',
                        ];
                        foreach ($fields as $label => $fieldKey): 
                            $nextOrderType = ($orderField == $fieldKey && $orderType == 'ASC') ? 'DESC' : 'ASC';
                    ?>
                        <th>
                            <a href="<?= base_url('/colaborador?search=' . esc($search) . '&orderField=' . esc($fieldKey) . '&orderType=' . esc($nextOrderType)) ?>" class="text-white text-decoration-none">
                                <?= esc($label) ?>
                                <?= $orderField == $fieldKey ? ($orderType == 'ASC' ? '▲' : '▼') : '' ?>
                            </a>
                        </th>
                    <?php endforeach; ?>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($usuarios)): ?>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= esc($usuario['id']) ?></td>
                            <td><?= esc($usuario['roles'] ?? 'Sin roles') ?></td>
                            <td><?= esc($usuario['usuario']) ?></td>
                            <td><?= esc($usuario['nombres']) ?></td>
                            <td><?= esc($usuario['apellido_paterno'] . ' ' . $usuario['apellido_materno']) ?></td>
                            <td><?= esc($usuario['identificacion_descripcion']) ?></td>
                            <td><?= esc($usuario['direccion'] ?? 'No registrada') ?></td>
                            <td><?= esc($usuario['emails'] ?? 'No registrado') ?></td>
                            <td><?= esc($usuario['telefonos'] ?? 'No registrado') ?></td>
                            <td>
							    <a href="<?= base_url('/colaborador/editar/' . $usuario['id']) ?>" 
							       class="btn btn-primary btn-sm mb-1" 
							       data-bs-toggle="tooltip" 
							       data-bs-placement="top" 
							       title="Editar">
							        <i class="fas fa-edit"></i>
							    </a>
							    <a href="<?= base_url('/colaborador/eliminar/' . $usuario['id']) ?>" 
							       class="btn btn-danger btn-sm mb-1" 
							       data-bs-toggle="tooltip" 
							       data-bs-placement="top" 
							       title="Eliminar">
							        <i class="fas fa-trash"></i>
							    </a>
							    <a href="<?= base_url('/colaborador/detalle/' . $usuario['id']) ?>" 
							       class="btn btn-info btn-sm" 
							       data-bs-toggle="tooltip" 
							       data-bs-placement="top" 
							       title="Ver Detalle">
							        <i class="fas fa-eye"></i>
							    </a>
							</td>


                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center text-muted">No hay usuarios activos.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center">
        <?= $pager->links() ?>
    </div>
</div>

<?= $footer; ?>
