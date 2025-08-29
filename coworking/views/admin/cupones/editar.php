<?php require_once __DIR__ . '/../../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cupón</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Editar Cupón</h2>

    <?php if (isset($_SESSION['error'])): mostrarMensaje('danger', $_SESSION['error']); unset($_SESSION['error']); endif; ?>
    <?php if (isset($_SESSION['success'])): mostrarMensaje('success', $_SESSION['success']); unset($_SESSION['success']); endif; ?>

    <form method="POST" action="?c=adminCupones&a=actualizar">
        <input type="hidden" name="id" value="<?= $cupon['id'] ?>">

        <div class="mb-3">
            <label for="codigo" class="form-label">Código</label>
            <input type="text" name="codigo" id="codigo" class="form-control" required value="<?= htmlspecialchars($cupon['codigo'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="descuento" class="form-label">Descuento (%)</label>
            <input type="number" name="descuento" id="descuento" class="form-control" min="1" max="100" required value="<?= htmlspecialchars($cupon['descuento'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="fecha_expiracion" class="form-label">Fecha de expiración</label>
            <input type="date" name="fecha_expiracion" id="fecha_expiracion" class="form-control" required value="<?= $cupon['fecha_expiracion'] ?? '' ?>">
        </div>

        <div class="mb-3">
            <label for="usuario_id" class="form-label">Asignar a (opcional)</label>
            <select name="usuario_id" id="usuario_id" class="form-select">
                <option value="">Todos (Cupón general)</option>
                <?php foreach ($usuarios as $u): ?>
                    <option value="<?= $u['id'] ?>" <?= ($cupon['usuario_id'] == $u['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($u['nombre_completo']) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">Guardar cambios</button>
            <a href="?c=adminCupones&a=index" class="btn btn-secondary">← Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
