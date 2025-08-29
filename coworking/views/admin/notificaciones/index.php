<?php require_once __DIR__ . '/../../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Notificaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Historial de Notificaciones</h2>

    <form method="GET" class="row align-items-end g-3 mb-4">
        <input type="hidden" name="c" value="adminNotificaciones">
        <input type="hidden" name="a" value="index">

        <div class="col-md-3">
            <label class="form-label">Tipo de notificación</label>
            <select name="tipo" class="form-select">
                <option value="">Todas</option>
                <option value="cupon" <?= ($_GET['tipo'] ?? '') === 'cupon' ? 'selected' : '' ?>>Cupón</option>
                <option value="cancelacion" <?= ($_GET['tipo'] ?? '') === 'cancelacion' ? 'selected' : '' ?>>Cancelación</option>
                <option value="confirmacion" <?= ($_GET['tipo'] ?? '') === 'confirmacion' ? 'selected' : '' ?>>Confirmación</option>
                <option value="recordatorio" <?= ($_GET['tipo'] ?? '') === 'recordatorio' ? 'selected' : '' ?>>Recordatorio</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Nombre del usuario</label>
            <input type="text" name="usuario_nombre" class="form-control"
                   placeholder="Buscar por nombre"
                   value="<?= htmlspecialchars($_GET['usuario_nombre'] ?? '') ?>">
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
            <a href="?c=adminNotificaciones&a=index" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
    </form>

    <?php if (empty($notificaciones)): ?>
        <div class="alert alert-info">No se han encontrado notificaciones con los filtros aplicados.</div>
    <?php else: ?>
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Tipo</th>
                    <th>Mensaje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notificaciones as $n): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($n['fecha_envio'])) ?></td>
                        <td><?= htmlspecialchars($n['nombre_completo']) ?></td>
                        <td>
                            <?php
                                $tipo = mb_strtolower($n['tipo_notificacion']);
                                $badge = 'secondary';
                                if ($tipo === 'cupon' || $tipo === 'cupón') $badge = 'info';
                                elseif ($tipo === 'cancelacion' || $tipo === 'cancelación') $badge = 'danger';
                                elseif ($tipo === 'confirmacion' || $tipo === 'confirmación') $badge = 'success';
                                elseif ($tipo === 'recordatorio') $badge = 'warning';
                            ?>
                            <span class="badge bg-<?= $badge ?>"><?= ucfirst($tipo) ?></span>
                        </td>
                        <td><?= nl2br(htmlspecialchars($n['mensaje'])) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <?php if ($total_paginas > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                            <a class="page-link"
                               href="?c=adminNotificaciones&a=index&<?= http_build_query(array_merge($_GET, ['pagina' => $i])) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>

    <div class="mt-4">
        <a href="?c=admin&a=index" class="btn btn-secondary">← Volver al panel</a>
    </div>
</div>
</body>
</html>
