<?php require_once __DIR__ . '/../../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Valoraciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2 class="mb-4">Gestión de Valoraciones</h2>

    <?php if (isset($_SESSION['success'])): mostrarMensaje('success', $_SESSION['success']); unset($_SESSION['success']); endif; ?>

    <form method="GET" class="row g-3 mb-4 align-items-end">
        <input type="hidden" name="c" value="adminValoraciones">

        <div class="col-md-3">
            <label class="form-label">Nombre del usuario</label>
            <input type="text" name="usuario_nombre" class="form-control" value="<?= htmlspecialchars($_GET['usuario_nombre'] ?? '') ?>">
        </div>

        <div class="col-md-3">
            <label class="form-label">Nombre del espacio</label>
            <input type="text" name="espacio_nombre" class="form-control" value="<?= htmlspecialchars($_GET['espacio_nombre'] ?? '') ?>">
        </div>

        <div class="col-md-2">
            <label class="form-label">Desde</label>
            <input type="date" name="fecha_inicio" class="form-control" value="<?= $_GET['fecha_inicio'] ?? '' ?>">
        </div>

        <div class="col-md-2">
            <label class="form-label">Hasta</label>
            <input type="date" name="fecha_fin" class="form-control" value="<?= $_GET['fecha_fin'] ?? '' ?>">
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            <a href="?c=adminValoraciones" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Espacio</th>
                <th>Mensaje</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($valoraciones as $v): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($v['fecha_valoracion'])) ?></td>
                    <td><?= htmlspecialchars($v['nombre_completo']) ?></td>
                    <td><?= htmlspecialchars($v['espacio']) ?></td>
                    <td><?= nl2br(htmlspecialchars($v['mensaje'])) ?></td>
                    <td>
                        <a href="?c=adminValoraciones&a=eliminar&id=<?= $v['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta valoración?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <?php if ($total_paginas > 1): ?>
        <nav>
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                        <a class="page-link" href="?c=adminValoraciones&<?= http_build_query(array_merge($_GET, ['pagina' => $i])) ?>"><?= $i ?></a>
                    </li>
                <?php endfor ?>
            </ul>
        </nav>
    <?php endif; ?>

    <a href="?c=admin&a=index" class="btn btn-secondary mt-4">← Volver al panel</a>
</div>