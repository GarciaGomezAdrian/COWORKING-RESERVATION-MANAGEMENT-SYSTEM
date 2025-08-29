<?php require_once __DIR__ . '/partials/navbar.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Coworking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .imagen-espacio {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }
    </style>
    <script>
        function limitarFechas() {
            const inicio = document.getElementById("fecha_inicio");
            const fin = document.getElementById("fecha_fin");

            inicio.addEventListener("change", () => {
                fin.min = inicio.value;
                if (fin.value < inicio.value) {
                    fin.value = "";
                }
            });
        }

        window.onload = limitarFechas;
    </script>
</head>
<body>
<div class="container mt-4">
    <h2>Buscar espacios disponibles</h2>
    <form method="GET" class="row g-3 mb-4">
        <input type="hidden" name="c" value="home">
        <input type="hidden" name="a" value="index">
        <div class="col-md-4">
            <label for="fecha_inicio" class="form-label">Fecha inicio</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required value="<?= htmlspecialchars($fecha_inicio ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label for="fecha_fin" class="form-label">Fecha fin</label>
            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required value="<?= htmlspecialchars($fecha_fin ?? '') ?>">
        </div>
        <div class="col-md-4 align-self-end">
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
        </div>
    </form>

    <div class="row">
        <?php if (!empty($espacios)): ?>
            <?php foreach ($espacios as $espacio): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100" onclick="window.location.href='?c=espacio&a=ver&id=<?= $espacio['id'] ?>&fecha_inicio=<?= $fecha_inicio ?>&fecha_fin=<?= $fecha_fin ?>'" style="cursor: pointer;">
                        <?php
                            $img_file = "img/espacios/{$espacio['id']}.jpg";
                            $img_path = __DIR__ . '/../public/' . $img_file;
                            if (!file_exists($img_path)) {
                                $img_file = "img/espacios/default.jpg";
                            }
                        ?>
                        <img src="<?= $img_file ?>" class="imagen-espacio" alt="Espacio">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($espacio['nombre']) ?></h5>
                            <p class="card-text">Capacidad: <?= $espacio['capacidad'] ?> personas</p>
                            <p class="card-text">Precio: <?= number_format($espacio['precio_dia'], 2) ?> €/día</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">No hay espacios disponibles para las fechas seleccionadas.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
