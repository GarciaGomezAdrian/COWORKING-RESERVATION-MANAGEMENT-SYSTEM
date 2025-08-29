<?php require_once __DIR__ . '/../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Panel de Administración</h2>

    <div class="list-group">
        <a href="?c=adminusuarios&a=index" class="list-group-item list-group-item-action">👥 Gestión de usuarios</a>
        <a href="?c=adminespacios&a=index" class="list-group-item list-group-item-action">🏢 Gestión de espacios</a>
        <a href="?c=adminreservas&a=index" class="list-group-item list-group-item-action">📅 Gestión de reservas</a>
        <a href="?c=admincupones&a=index" class="list-group-item list-group-item-action">🏷️ Gestión de cupones</a>
        <a href="?c=adminvaloraciones&a=index" class="list-group-item list-group-item-action">⭐ Gestión de valoraciones</a>
        <a href="?c=adminnotificaciones&a=index" class="list-group-item list-group-item-action">📨 Notificaciones enviadas</a>
    </div>
</div>
</body>
</html>
