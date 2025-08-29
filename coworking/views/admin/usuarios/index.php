<?php require_once __DIR__ . '/../../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Gestión de Usuarios</h2>

    <?php if (isset($_SESSION['success'])): mostrarMensaje('success', $_SESSION['success']); unset($_SESSION['success']); endif; ?>
    <?php if (isset($_SESSION['error'])): mostrarMensaje('danger', $_SESSION['error']); unset($_SESSION['error']); endif; ?>

    <form method="GET" class="row g-3 mb-4 align-items-end">
        <input type="hidden" name="c" value="adminusuarios">
    
        <div class="col-md-6 col-lg-4">
            <input type="text" id="busqueda" name="busqueda" class="form-control" placeholder="Buscar por nombre o email" value="<?= htmlspecialchars($_GET['busqueda'] ?? '') ?>" onkeydown="if (event.key === 'Enter') this.form.submit();">
        </div>

        <div class="col-md-6 col-lg-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
            <a href="?c=adminusuarios&a=index" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre completo</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Registrado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['nombre_completo']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <?php if ($u['tipo_usuario'] === 'admin'): ?>
                            <span class="badge bg-primary">Administrador</span>
                        <?php else: ?>
                            <span class="badge bg-success">Cliente</span>
                        <?php endif; ?>
                    </td>
                    <td><?= formatearFecha($u['fecha_registro']) ?></td>
                    <td>
                        <a href="?c=adminUsuarios&a=editar&id=<?= $u['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="?c=adminUsuarios&a=eliminar&id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">Eliminar</a>
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
                        <a class="page-link" href="?c=adminusuarios&busqueda=<?= urlencode($busqueda) ?>&pagina=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor ?>
            </ul>
        </nav>
    <?php endif; ?>

    <a href="?c=admin&a=index" class="btn btn-secondary mt-3">← Volver al panel</a>
</div>
</body>
</html>
