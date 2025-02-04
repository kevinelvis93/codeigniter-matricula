<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Operaciones</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php
    // Verificar si el usuario está logueado
    $session = session();
    $isLoggedIn = $session->get('isLoggedIn');

    if ($isLoggedIn): // Mostrar el menú si está logueado
    ?>  
        <?php if (isset($menu) && !empty($menu)): ?> <!-- Verifica si el menú está definido -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?= base_url('/plantilla') ?>">Sistema</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <?php foreach ($menu as $item): ?>
                            <li class="nav-item <?= isset($item['submenus']) && count($item['submenus']) > 0 ? 'dropdown' : '' ?>">
                                <a class="nav-link <?= isset($item['submenus']) && count($item['submenus']) > 0 ? 'dropdown-toggle' : '' ?>"
                                   href="<?= base_url($item['ruta'] ?? '#') ?>"
                                   <?= isset($item['submenus']) && count($item['submenus']) > 0 ? 'role="button" data-bs-toggle="dropdown" aria-expanded="false"' : '' ?>>
                                    <i class="<?= esc($item['icono'] ?? 'fa-default') ?>"></i> <?= esc($item['nombre'] ?? 'Sin Nombre') ?>
                                </a>
                                <?php if (isset($item['submenus']) && count($item['submenus']) > 0): ?>
                                    <ul class="dropdown-menu">
                                        <?php foreach ($item['submenus'] as $submenu): ?>
                                            <li><a class="dropdown-item" href="<?= base_url($submenu['ruta'] ?? '#') ?>"><?= esc($submenu['nombre'] ?? 'Sin Nombre') ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>

                        <!-- Opción de Cerrar Sesión -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/logout') ?>">
                                <i class="fa fa-sign-out-alt"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <?php endif; ?>
        
    <?php
    endif; // Fin del chequeo de sesión
    ?>
