<?php require_once __DIR__ . '/../../partials/navbar.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Editar Usuario</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <?php mostrarMensaje('success', $_SESSION['success']); unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <?php mostrarMensaje('danger', $_SESSION['error']); unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="?c=adminusuarios&a=actualizar">
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

        <div class="mb-3">
            <label>Nombre completo</label>
            <input type="text" name="nombre" class="form-control" required value="<?= htmlspecialchars($usuario['nombre_completo']) ?>">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($usuario['email']) ?>">
        </div>

        <div class="mb-3">
            <label>Tipo de usuario</label>
            <select name="tipo_usuario" class="form-select" required>
                <option value="cliente" <?= $usuario['tipo_usuario'] === 'cliente' ? 'selected' : '' ?>>Cliente</option>
                <option value="admin" <?= $usuario['tipo_usuario'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Guardar cambios</button>
        <a href="?c=adminusuarios" class="btn btn-secondary">← Cancelar</a>
    </form>
</div>
</body>
</html>
