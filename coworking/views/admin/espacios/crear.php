<?php require_once __DIR__ . '/../../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Espacio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Crear nuevo espacio</h2>

    <?php if (isset($_SESSION['error'])): mostrarMensaje('danger', $_SESSION['error']); unset($_SESSION['error']); endif; ?>
    <?php if (isset($_SESSION['success'])): mostrarMensaje('success', $_SESSION['success']); unset($_SESSION['success']); endif; ?>

    <form method="POST" action="?c=adminEspacios&a=guardar" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label for="capacidad" class="form-label">Capacidad</label>
            <input type="number" name="capacidad" id="capacidad" class="form-control" min="1" required>
        </div>
        <div class="mb-3">
            <label for="precio_dia" class="form-label">Precio por día (€)</label>
            <input type="number" step="0.01" name="precio_dia" id="precio_dia" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="ubicacion" class="form-label">Ubicación</label>
            <input type="text" name="ubicacion" id="ubicacion" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen principal (máx. 800x800)</label>
            <input type="file" name="imagen" id="imagen" class="form-control" accept="image/jpeg,image/png">
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="?c=adminEspacios&a=index" class="btn btn-secondary">← Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
