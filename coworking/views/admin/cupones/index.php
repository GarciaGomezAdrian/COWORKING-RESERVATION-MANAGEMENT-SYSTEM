<?php require_once __DIR__ . '/../../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Cupones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Gestión de Cupones</h2>

    <?php if (isset($_SESSION['success'])): mostrarMensaje('success', $_SESSION['success']); unset($_SESSION['success']); endif; ?>

    <form method="GET" class="row g-3 align-items-end mb-3">
        <input type="hidden" name="c" value="adminCupones">

        <div class="col-md-4">
            <input 
                type="text" 
                name="busqueda" 
                class="form-control" 
                placeholder="Buscar por código" 
                value="<?= htmlspecialchars($_GET['busqueda'] ?? '') ?>" 
                onkeydown="if (event.key === 'Enter') this.form.submit();"
            >
        </div>

        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
            <a href="?c=adminCupones" class="btn btn-outline-secondary w-100">Reset</a>
        </div>

        <div class="col-md-3 text-end ms-auto">
            <a href="?c=adminCupones&a=crear" class="btn btn-success w-100">+ Nuevo Cupón</a>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Código</th>
                <th>Descuento</th>
                <th>Expira</th>
                <th>Asignado a</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cupones as $c): ?>
                <?php
                    $vigente = strtotime($c['fecha_expiracion']) >= strtotime(date('d-m-Y'));
                    $badgeClass = $vigente ? 'bg-success' : 'bg-danger';
                ?>
                <tr>
                    <td><?= htmlspecialchars($c['codigo']) ?></td>
                    <td><?= $c['descuento'] ?>%</td>
                    <td>
                        <span class="badge <?= $badgeClass ?>">
                            <?= date('d/m/Y', strtotime($c['fecha_expiracion'])) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($c['nombre_completo'] ?? 'General') ?></td>
                    <td>
                        <a href="?c=adminCupones&a=editar&id=<?= $c['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="?c=adminCupones&a=eliminar&id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar cupón?')">Eliminar</a>
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
                        <a class="page-link" href="?c=adminCupones&busqueda=<?= urlencode($busqueda) ?>&pagina=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor ?>
            </ul>
        </nav>
    <?php endif; ?>

    <a href="?c=admin&a=index" class="btn btn-secondary mt-3">← Volver al panel</a>
</div>
</body>
</html>
