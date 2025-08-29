<?php require_once __DIR__ . '/../../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Gestión de Reservas</h2>

    <?php if (isset($_SESSION['success'])): mostrarMensaje('success', $_SESSION['success']); unset($_SESSION['success']); endif; ?>
    <?php if (isset($_SESSION['error'])): mostrarMensaje('danger', $_SESSION['error']); unset($_SESSION['error']); endif; ?>

    <form method="GET" class="row g-3 mb-4 align-items-end">
        <input type="hidden" name="c" value="adminReservas">
        <input type="hidden" name="a" value="index">

        <div class="col-md-3">
            <label class="form-label">Cliente</label>
            <input type="text" name="cliente" class="form-control" value="<?= htmlspecialchars($_GET['cliente'] ?? '') ?>" placeholder="Nombre del cliente">
        </div>

        <div class="col-md-3">
            <label class="form-label">Fecha inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" value="<?= $_GET['fecha_inicio'] ?? '' ?>">
        </div>

        <div class="col-md-3">
            <label class="form-label">Fecha fin</label>
            <input type="date" name="fecha_fin" class="form-control" value="<?= $_GET['fecha_fin'] ?? '' ?>">
        </div>

        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            <a href="?c=adminReservas&a=index" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Espacio</th>
                <th>Fecha inicio</th>
                <th>Fecha fin</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservas as $r): ?>
                <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= htmlspecialchars($r['nombre_completo']) ?></td>
                    <td><?= htmlspecialchars($r['espacio']) ?></td>
                    <td><?= formatearFecha($r['fecha_inicio']) ?></td>
                    <td><?= formatearFecha($r['fecha_fin']) ?></td>
                    <td>
                        <span class="badge bg-<?= $r['estado'] === 'cancelada' ? 'danger' : 'success' ?>">
                            <?= ucfirst($r['estado']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="?c=adminReservas&a=editar&id=<?= $r['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <?php if ($r['estado'] !== 'cancelada'): ?>
                            <a href="?c=adminReservas&a=cancelar&id=<?= $r['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Cancelar esta reserva?')">Cancelar</a>
                        <?php endif ?>
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
                        <a class="page-link" href="?c=adminReservas&a=index&cliente=<?= urlencode($cliente) ?>&fecha_inicio=<?= $fecha_inicio ?>&fecha_fin=<?= $fecha_fin ?>&pagina=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor ?>
            </ul>
        </nav>
    <?php endif; ?>

    <a href="?c=admin&a=index" class="btn btn-secondary mt-3">← Volver al panel</a>
</div>
</body>
</html>
