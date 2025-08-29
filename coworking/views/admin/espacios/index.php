<?php require_once __DIR__ . '/../../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Espacios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Gestión de Espacios</h2>

    <?php if (isset($_SESSION['success'])): mostrarMensaje('success', $_SESSION['success']); unset($_SESSION['success']); endif; ?>
    <?php if (isset($_SESSION['error'])): mostrarMensaje('danger', $_SESSION['error']); unset($_SESSION['error']); endif; ?>

    <form method="GET" class="row g-3 mb-3">
        <input type="hidden" name="c" value="adminEspacios">
        <input type="hidden" name="a" value="index">
        <div class="col-md-6">
            <input type="text" name="busqueda" class="form-control" placeholder="Buscar por nombre" value="<?= htmlspecialchars($_GET['busqueda'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a href="?c=adminEspacios&a=index" class="btn btn-outline-secondary">Reset</a>
        </div>
        <div class="col-md-3 text-end">
            <a href="?c=adminEspacios&a=crear" class="btn btn-success">+ Añadir nuevo espacio</a>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Capacidad</th>
                <th>Precio Día</th>
                <th>Ubicación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($espacios as $e): ?>
                <tr>
                    <td><?= $e['id'] ?></td>
                    <td><?= htmlspecialchars($e['nombre']) ?></td>
                    <td><?= $e['capacidad'] ?></td>
                    <td><?= number_format($e['precio_dia'], 2) ?> €</td>
                    <td><?= htmlspecialchars($e['ubicacion']) ?></td>
                    <td>
                        <a href="?c=adminEspacios&a=editar&id=<?= $e['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="?c=adminEspacios&a=eliminar&id=<?= $e['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este espacio?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <?php if ($total_paginas > 1): ?>
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                        <a class="page-link" href="?c=adminEspacios&a=index&pagina=<?= $i ?>&busqueda=<?= urlencode($busqueda ?? '') ?>"><?= $i ?></a>
                    </li>
                <?php endfor ?>
            </ul>
        </nav>
    <?php endif; ?>

    <div class="mt-4">
        <a href="?c=admin&a=index" class="btn btn-secondary">← Volver al panel</a>
    </div>
</div>
</body>
</html>
