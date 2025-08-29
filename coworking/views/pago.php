<?php require_once __DIR__ . '/partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Resumen de tu reserva</h2>

    <div class="mb-3">
        <p><strong>Espacio:</strong> <?= htmlspecialchars($espacio['nombre']) ?></p>
        <p><strong>Ubicación:</strong> <?= htmlspecialchars($espacio['ubicacion']) ?></p>
        <p><strong>Fechas:</strong> <?= $fecha_inicio ?> a <?= $fecha_fin ?></p>

        <?php
            $dias = (strtotime($fecha_fin) - strtotime($fecha_inicio)) / 86400 + 1;
            $precio_total = $espacio['precio_dia'] * $dias;
        ?>
        <p><strong>Duración:</strong> <?= $dias ?> día(s)</p>
        <p><strong>Precio por día:</strong> <?= number_format($espacio['precio_dia'], 2) ?> €</p>
        <p><strong>Precio total:</strong> <span id="precioOriginal"><?= number_format($precio_total, 2) ?></span> €</p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif ?>

    <form method="POST" action="?c=pago&a=procesar">
        <input type="hidden" name="espacio_id" value="<?= $espacio['id'] ?>">
        <input type="hidden" name="fecha_inicio" value="<?= $fecha_inicio ?>">
        <input type="hidden" name="fecha_fin" value="<?= $fecha_fin ?>">

        <div class="mb-3">
            <label for="tarjeta" class="form-label">Tarjeta (ficticia)</label>
            <input type="text" name="tarjeta" id="tarjeta" class="form-control" required placeholder="1234 5678 9012 3456">
        </div>

        <div class="mb-3">
            <label for="cupon" class="form-label">Cupón de descuento</label>
            <div class="input-group">
                <input type="text" name="cupon" id="cupon" class="form-control" placeholder="CUPON2025">
                <button type="button" class="btn btn-outline-primary" onclick="aplicarCupon()">Aplicar cupón</button>
            </div>
        </div>

        <div id="resumenCupon" class="border rounded p-3 mt-3 d-none">
            <h5>Resumen del Cupón</h5>
            <p><strong>Código:</strong> <span id="codigoMostrar"></span></p>
            <p><strong>Descuento:</strong> <span id="porcentajeMostrar"></span>%</p>
            <p><strong>Descuento aplicado:</strong> -<span id="descuentoMostrar"></span> €</p>
            <p><strong>Total con descuento:</strong> <strong><span id="totalFinalMostrar"></span> €</strong></p>
        </div>

        <button type="submit" class="btn btn-success w-100 mt-3">Pagar</button>
    </form>

    <a href="?c=espacio&a=ver&id=<?= $espacio['id'] ?>&fecha_inicio=<?= $fecha_inicio ?>&fecha_fin=<?= $fecha_fin ?>" class="btn btn-secondary mt-3">← Volver</a>
</div>

<script>
function aplicarCupon() {
    const codigo = document.getElementById("cupon").value.trim();
    const resumen = document.getElementById("resumenCupon");
    const precioOriginal = parseFloat(document.getElementById("precioOriginal").innerText.replace(",", "."));

    if (!codigo) return;

    fetch("?c=ajax&a=validarCupon", {
        method: "POST",
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: "codigo=" + encodeURIComponent(codigo)
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
            resumen.classList.add("d-none");
            return;
        }

        const descuento = (precioOriginal * data.descuento / 100).toFixed(2);
        const totalFinal = (precioOriginal - descuento).toFixed(2);

        document.getElementById("codigoMostrar").innerText = data.codigo;
        document.getElementById("porcentajeMostrar").innerText = data.descuento;
        document.getElementById("descuentoMostrar").innerText = descuento;
        document.getElementById("totalFinalMostrar").innerText = totalFinal;
        resumen.classList.remove("d-none");
    });
}
</script>

</body>
</html>

