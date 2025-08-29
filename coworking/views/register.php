
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-body">
            <h5 class="card-title text-center">Registro de Usuario</h5>
            <?php
                if (isset($_SESSION['error'])) mostrarMensaje('danger', $_SESSION['error']);
                if (isset($_SESSION['success'])) mostrarMensaje('success', $_SESSION['success']);
                unset($_SESSION['error'], $_SESSION['success']);
            ?>
            <form method="POST" action="?c=auth&a=registroPost">
                <div class="mb-3">
                    <label>Nombre completo</label>
                    <input type="text" name="nombre" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Contraseña</label>
                    <input type="password" name="password" class="form-control" required
                        pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,20}"
                        title="Debe tener entre 8 y 20 caracteres, e incluir mayúsculas, minúsculas, números y símbolos.">
                </div>
                <button type="submit" class="btn btn-success w-100">Registrarse</button>
            </form>
            <div class="mt-3 text-center">
                ¿Ya tienes cuenta? <a href="?c=auth&a=login">Inicia sesión</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
