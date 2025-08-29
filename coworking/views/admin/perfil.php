<?php require_once __DIR__ . '/../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil (Admin)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .invalid-feedback {
            display: none;
        }
        .is-invalid + .invalid-feedback {
            display: block;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Mi Perfil</h2>

    <?php if (isset($_SESSION['success'])): mostrarMensaje('success', $_SESSION['success']); unset($_SESSION['success']); endif; ?>
    <?php if (isset($_SESSION['error'])): mostrarMensaje('danger', $_SESSION['error']); unset($_SESSION['error']); endif; ?>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Información del perfil</h5>
                    <div class="mb-3">
                        <label class="form-label">Nombre completo</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($usuario['nombre_completo']) ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha de registro</label>
                        <input type="text" class="form-control" value="<?= date('d/m/Y', strtotime($usuario['fecha_registro'])) ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo de usuario</label>
                        <input type="text" class="form-control" value="<?= ucfirst($usuario['tipo_usuario']) ?>" disabled>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Cambiar Contraseña</h5>
                    <form method="POST" action="?c=admin&a=actualizarContrasena" class="row g-3" id="form-cambio-admin">
                        <div class="col-12">
                            <label for="contrasena_actual" class="form-label">Contraseña actual</label>
                            <input type="password" id="contrasena_actual" name="contrasena_actual" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label for="nueva_contrasena" class="form-label">Nueva contraseña</label>
                            <input type="password" id="nueva_contrasena" name="nueva_contrasena" class="form-control" required>
                            <div class="invalid-feedback" id="error-nueva"></div>
                        </div>
                        <div class="col-12">
                            <label for="confirmar_contrasena" class="form-label">Confirmar nueva contraseña</label>
                            <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" class="form-control" required>
                            <div class="invalid-feedback" id="error-confirmar"></div>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning">Actualizar contraseña</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <a href="?c=admin&a=index" class="btn btn-secondary">&larr; Volver al panel</a>
</div>

<script>
document.getElementById('form-cambio-admin').addEventListener('submit', function(e) {
    const nueva = document.getElementById('nueva_contrasena');
    const confirmar = document.getElementById('confirmar_contrasena');
    const errorNueva = document.getElementById('error-nueva');
    const errorConfirmar = document.getElementById('error-confirmar');

    let valido = true;

    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,20}$/;

    nueva.classList.remove('is-invalid');
    confirmar.classList.remove('is-invalid');
    errorNueva.textContent = '';
    errorConfirmar.textContent = '';

    if (!regex.test(nueva.value)) {
        errorNueva.textContent = "La contraseña debe tener entre 8 y 20 caracteres, incluir mayúsculas, minúsculas, números y símbolos.";
        nueva.classList.add('is-invalid');
        valido = false;
    }

    if (nueva.value !== confirmar.value) {
        errorConfirmar.textContent = "Las contraseñas no coinciden.";
        confirmar.classList.add('is-invalid');
        valido = false;
    }

    if (!valido) {
        e.preventDefault();
    }
});
</script>
</body>
</html>
