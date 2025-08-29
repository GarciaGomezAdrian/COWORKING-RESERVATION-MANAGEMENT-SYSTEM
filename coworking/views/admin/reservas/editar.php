<?php require_once __DIR__ . '/../../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Editar Reserva #<?= $reserva['id'] ?></h2>

    <form method="POST" action="?c=adminReservas&a=actualizar">
        <input type="hidden" name="id" value="<?= $reserva['id'] ?>">

        <div class="mb-3">
            <label class="form-label">Espacio</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($reserva['espacio']) ?>" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Cliente</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($reserva['nombre_completo']) ?>" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" required value="<?= $reserva['fecha_inicio'] ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha fin</label>
            <input type="date" name="fecha_fin" class="form-control" required value="<?= $reserva['fecha_fin'] ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
                <option value="confirmada" <?= $reserva['estado'] === 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                <option value="cancelada" <?= $reserva['estado'] === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">Guardar cambios</button>
            <a href="?c=adminReservas&a=index" class="btn btn-secondary">← Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
