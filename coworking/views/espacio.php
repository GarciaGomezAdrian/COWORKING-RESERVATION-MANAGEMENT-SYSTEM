<?php require_once __DIR__ . '/partials/navbar.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($espacio['nombre']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .calendario td.disabled {
            background-color: #f8d7da;
            pointer-events: none;
        }
        .calendario td.available {
            background-color: #d4edda;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <a href="?c=home&a=index&fecha_inicio=<?= urlencode($fecha_inicio) ?>&fecha_fin=<?= urlencode($fecha_fin) ?>" class="btn btn-secondary mb-3">← Volver a la búsqueda</a>

    <div class="row">
        <div class="col-md-6">
            <h2><?= htmlspecialchars($espacio['nombre']) ?></h2>
            <p><strong>Ubicación:</strong> <?= htmlspecialchars($espacio['ubicacion']) ?></p>
            <p><strong>Capacidad:</strong> <?= $espacio['capacidad'] ?> personas</p>
            <p><strong>Precio:</strong> <?= number_format($espacio['precio_dia'], 2) ?> €/día</p>
            <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($espacio['descripcion'])) ?></p>

            <?php
            $img_file = "img/espacios/{$espacio['id']}.jpg";
            $img_path = __DIR__ . '/../public/' . $img_file;
            if (!file_exists($img_path)) {
                $img_file = "img/espacios/default.jpg";
            }
            ?>
            <img src="<?= $img_file ?>" class="img-fluid rounded mb-3" alt="Espacio principal">

        </div>

        <div class="col-md-6">
            <h4>Reservar este espacio</h4>
            <form method="GET" action="index.php">
                <input type="hidden" name="c" value="pago">
                <input type="hidden" name="a" value="iniciar">
                <input type="hidden" name="id" value="<?= $espacio['id'] ?>">

                <div class="mb-3">
                    <label>Fecha inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required value="<?= htmlspecialchars($fecha_inicio) ?>">
                </div>
                <div class="mb-3">
                    <label>Fecha fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required value="<?= htmlspecialchars($fecha_fin) ?>">
                </div>

                <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo_usuario'] === 'cliente'): ?>
                    <button type="submit" class="btn btn-success w-100">Confirmar reserva</button>
                <?php else: ?>
                    <div class="alert alert-warning">Debes iniciar sesión como cliente para reservar.</div>
                <?php endif ?>
            </form>

            <script>
                const ocupados = <?= json_encode($diasOcupados) ?>;

                function deshabilitarDias() {
                    const inicio = document.getElementById("fecha_inicio");
                    const fin = document.getElementById("fecha_fin");

                    const ocupadas = new Set();

                    ocupados.forEach(r => {
                        let fi = new Date(r.fecha_inicio);
                        let ff = new Date(r.fecha_fin);
                        for (let d = new Date(fi); d <= ff; d.setDate(d.getDate() + 1)) {
                            ocupadas.add(d.toISOString().split('T')[0]);
                        }
                    });

                    inicio.addEventListener("change", () => {
                        fin.min = inicio.value;
                    });

                    [inicio, fin].forEach(campo => {
                        campo.addEventListener("input", () => {
                            if (ocupadas.has(campo.value)) {
                                alert("Día no disponible.");
                                campo.value = '';
                            }
                        });
                    });
                }

                window.onload = deshabilitarDias;
            </script>
        </div>
    </div>

    <hr>
    <h4>Valoraciones de otros usuarios</h4>
    <?php if (count($valoraciones) === 0): ?>
        <p>No hay valoraciones aún.</p>
    <?php else: ?>
        <?php foreach ($valoraciones as $val): ?>
            <div class="border rounded p-3 mb-2">
                <strong><?= htmlspecialchars($val['nombre_completo']) ?></strong> 
                <span class="text-muted">(<?= $val['fecha_valoracion'] ?>)</span>
                <p><?= nl2br(htmlspecialchars($val['mensaje'])) ?></p>
            </div>
        <?php endforeach ?>
    <?php endif ?>
</div>
</body>
</html>
