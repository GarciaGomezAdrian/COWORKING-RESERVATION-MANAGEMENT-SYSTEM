<?php require_once __DIR__ . '/partials/navbar.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .img-mini-reserva {
            max-width: 120px;
            max-height: 90px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Mis Reservas</h2>

    <?php if (empty($reservas)): ?>
        <div class="alert alert-info">
            No tienes reservas registradas.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($reservas as $r): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm" onclick="location.href='?c=usuario&a=detalleReserva&id=<?= urlencode($r['id']) ?>'" style="cursor:pointer;">
                        <div class="row g-0">
                            <div class="col p-3">
                                <div class="card-body">
                                    <h5 class="card-title mb-1">
                                        <?= htmlspecialchars($r['nombre']) ?>
                                        <?php if ($r['estado'] === 'cancelada'): ?>
                                            <span class="badge bg-danger ms-2">Cancelada</span>
                                        <?php else: ?>
                                            <span class="badge bg-success ms-2">Confirmada</span>
                                        <?php endif; ?>
                                    </h5>
                                    <p class="card-text mb-1">
                                        <strong>Desde:</strong> <?= htmlspecialchars(formatearFecha($r['fecha_inicio'])) ?><br>
                                        <strong>Hasta:</strong> <?= htmlspecialchars(formatearFecha($r['fecha_fin'])) ?>
                                    </p>
                                    <?php if (isset($r['precio_total'])): ?>
                                        <p class="card-text text-success mb-0">
                                            <strong>Total:</strong> <?= number_format($r['precio_total'], 2) ?> €
                                        </p>
                                    <?php endif ?>
                                </div>
                            </div>
                            <div class="col-auto d-flex align-items-center pe-3">
                                <?php
                                    $img_file = "img/espacios/{$r['espacio_id']}.jpg";
                                    $img_path = __DIR__ . '/../public/' . $img_file;
                                    if (!file_exists($img_path)) {
                                        $img_file = "img/espacios/default.jpg";
                                    }
                                ?>
                                <img src="<?= $img_file ?>" class="img-mini-reserva" alt="Espacio">
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php endif ?>
</div>
</body>
</html>
