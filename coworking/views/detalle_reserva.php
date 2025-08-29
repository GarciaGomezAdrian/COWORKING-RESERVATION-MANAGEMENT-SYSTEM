<?php require_once __DIR__ . '/partials/navbar.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .img-miniatura {
            max-width: 200px;
            max-height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Reserva: <?= htmlspecialchars($reserva['nombre']) ?></h2>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <p><strong>Ubicación:</strong> <?= htmlspecialchars($reserva['ubicacion']) ?></p>
                    <p><strong>Fechas:</strong> <?= htmlspecialchars(formatearFecha($reserva['fecha_inicio'])) ?> - <?= htmlspecialchars(formatearFecha($reserva['fecha_fin'])) ?></p>
                    <p>
                        <strong>Estado:</strong>
                        <?php if ($reserva['estado'] === 'confirmada'): ?>
                            <span class="badge bg-success">Confirmada</span>
                        <?php elseif ($reserva['estado'] === 'cancelada'): ?>
                            <span class="badge bg-danger">Cancelada</span>
                        <?php else: ?>
                            <span class="badge bg-secondary"><?= ucfirst($reserva['estado']) ?></span>
                        <?php endif; ?>
                    </p>
                    <p><strong>Precio por día:</strong> <?= number_format($reserva['precio_dia'], 2) ?> €</p>

                    <?php
                        $dias = (strtotime($reserva['fecha_fin']) - strtotime($reserva['fecha_inicio'])) / 86400 + 1;
                        $precio_total = $reserva['precio_dia'] * $dias;

                        echo "<p><strong>Duración:</strong> $dias día" . ($dias > 1 ? "s" : "") . "</p>";
                        echo "<p><strong>Precio original:</strong> " . number_format($precio_total, 2) . " €</p>";

                        require_once __DIR__ . '/../models/Cupon.php';
                        $cuponModel = new Cupon($pdo);
                        $cupon = $cuponModel->obtenerUltimoCuponValido($_SESSION['usuario']['id']);

                        if ($cupon) {
                            $descuento = $precio_total * ($cupon['descuento'] / 100);
                            $total_con_descuento = $precio_total - $descuento;
                    ?>
                            <div class="border rounded p-3 mt-3 bg-light">
                                <h5>Resumen del Cupón</h5>
                                <p><strong>Código:</strong> <?= htmlspecialchars($cupon['codigo']) ?></p>
                                <p><strong>Descuento:</strong> <?= $cupon['descuento'] ?>%</p>
                                <p><strong>Descuento aplicado:</strong> -<?= number_format($descuento, 2) ?> €</p>
                                <p><strong>Total con descuento:</strong> <strong><?= number_format($total_con_descuento, 2) ?> €</strong></p>
                            </div>
                    <?php
                        }
                    ?>

                    <p class="mt-3"><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($reserva['descripcion'])) ?></p>
                </div>
                <div class="col-md-4 text-end">
                    <?php
                        $img_file = "img/espacios/{$reserva['espacio_id']}.jpg";
                        $img_path = __DIR__ . '/../public/' . $img_file;
                        if (!file_exists($img_path)) {
                            $img_file = "img/espacios/default.jpg";
                        }
                    ?>
                    <img src="<?= $img_file ?>" class="img-miniatura mt-2" alt="Imagen del espacio">
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <a class="btn btn-primary" href="?c=factura&a=generar&id=<?= urlencode($reserva['id']) ?>" target="_blank">
                    Ver factura en PDF
                </a>
            </div>
        </div>
    </div>

    <?php if ($puede_valorar): ?>
        <div class="card border-success mb-4">
            <div class="card-body">
                <h5 class="card-title">Valora tu experiencia</h5>
                <form method="POST" action="?c=usuario&a=valorar">
                    <input type="hidden" name="reserva_id" value="<?= $reserva['id'] ?>">
                    <div class="mb-3">
                        <label for="mensaje" class="form-label">Tu valoración</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="4" required minlength="10" placeholder="Tu opinión sobre el espacio..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Enviar valoración</button>
                </form>
            </div>
        </div>
    <?php endif ?>

    <a href="?c=usuario&a=reservas" class="btn btn-secondary">← Volver a mis reservas</a>
</div>
</body>
</html>
